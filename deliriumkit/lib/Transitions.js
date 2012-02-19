var Transition=function(options){
	for(option in options){
		if(typeof options[option]!='undefined'){
			this.options[option]=options[option];
		}
	}	
	var me=this;
	this.go_again=function(){
		me.go();
	}
	this.start=this.current();
	return this;
	
}

Transition.prototype={
	done:false,
	strat:0,
	started:false,
	options:{
		animation:null,
		time:1000,		
		callback:function(){return true;},
		onStart:function(){return true;},
		onEnd:function(){return true;}
	},
	go:function(){
		if(!this.started){
			this.options.onStart();
			this.started=true;	
		}
		if(!this.time_out()){
			this.started=false;
			this.options.onEnd();			
			return;
		}		
		
		this.options.callback(this.more());
		setTimeout(this.go_again,1);
	},
	time_out:function(){		
		if(this.done){
			return this.last;
		}
		var current=this.current()-this.start;
		
		if(current>this.options.time){
			this.done=true;
			this.last=true;			
		}
		return true;
	},
	more:function(){		
		this.last = false;
		var current=this.current()-this.start;
		var percent = Math.min(1, (current) / this.options.time);
		return this.options.animation(percent);		
	},
	current:function(){
		return new Date().getTime();
	}
}
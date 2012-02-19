var Animations={
	linear:function(percent){
		return percent;
	},
	sine_curve:function(percent){		
		return (1 - Math.cos(percent * Math.PI)) / 2;
	}
}
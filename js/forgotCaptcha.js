/*
* @Author: Erik Viveros
* @Date:   2018-08-14 11:29:32
* @Last Modified by:   Erik Viveros
* @Last Modified time: 2018-08-14 11:29:37
*/
	var mainContainer=document.getElementById("registerCaptcha");
	var containerPWD=document.getElementById("captchaPWD");
	var widgetId1;
    var widgetId2;
    var getPwd=false;

	var onloadCallback = function() {

		widgetId1 = grecaptcha.render(
			mainContainer,
			{
				"sitekey" : "6LezcEUUAAAAAPmmOzTQckUo9MQDMqVjpRXxvY6D",
				"theme" : "dark",
				"size" : "normal"
			}
		) 
		widgetId2 = grecaptcha.render(
			containerPWD,
			{
				"sitekey" : "6LezcEUUAAAAAPmmOzTQckUo9MQDMqVjpRXxvY6D",
				"theme" : "dark",
				"size" : "normal",
				"callback" : function(){
					getPwd=true;
				},
				"expired-callback" : function(){
					getPwd=false;
				}
			}
		);

	}
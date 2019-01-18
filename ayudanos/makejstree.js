function loadTree() {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200) 
		{
			var sueperMegaJson = JSON.parse(this.responseText);
			var fByArbol = getValofJson(sueperMegaJson, "", "");
			document.getElementById("json").innerHTML=JSON.stringify(fByArbol)
		}
	};
	xhttp.open("GET", "dataTree.txt", true);
	xhttp.send();
}
function getValofJson(sueperMegaJson,input, unidades)
{
	var json = {};
	Object.keys(sueperMegaJson).forEach(function(k){		
		if(typeof(sueperMegaJson[k])==='object')
		{
			json[sueperMegaJson[k]["nombre"]]=getValofJson(sueperMegaJson[k]["hijos"], sueperMegaJson[k]["inputType"], sueperMegaJson[k]["unidades"]);		
	    }
	    else
	    {
	    	if(input =="1")
	    	{
	    		json["inputType"] = input ;
	    		if(unidades!= "null" && unidades!=null)
	    		{
	    			//alert(unidades);
	    			json["Unidades"] = unidades;
	    		}
	    	}
	    	else
	    	{
	    		if(input=="3")
	    		{
	    			tipo = "on";
	    		}
	    		else if(input=="2")
	    		{
	    			tipo = "text";
	    		}
	    		else if(input=="4")
	    		{
	    			tipo = "Si";
	    		}
	    		json["inputType"] = tipo; 
	    	}
	    }
	}, json);
	return json;
}
//var sueperMegaJson = loadTree();
function partOfTree(jsonResponse)
{
	var $textArea = $("#tree").find("textarea");
	var tree = $textArea.val();
	$("#InsertNewData").html("");
	$("#InsertNewData").append("<textarea name='jsonObject' class='hidden'>" +JSON.stringify(goOneWayTo(JSON.parse(tree), JSON.parse(jsonResponse), [])) + "</textarea>");
	//$("#InsertNewData").append("<textarea name='jsonObject' class='hidden'>" +JSON.stringify(goOneWay(JSON.parse(tree), [])) + "</textarea>");
	var count = $textArea.data("count");
	$textArea.data("count", count+1);
	//alert(count);
}

function getFeatures(idVersion)
{
	var xhttp = new XMLHttpRequest();
	var urlPost = "/ayudanos/firstChar.php";
	xhttp.open("POST", urlPost, true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200) 
		{
			partOfTree(this.responseText);
		}
	}
	xhttp.send("versionID="+idVersion);
}

function loadTreeFeature() 
{
	//var random = Math.floor(Math.random() * 7);
	var random = 2;
	switch (random) 
	{
		case 0:
			var tree = "featureTree/motor.txt";
			break;
		case 1:
			var tree = "featureTree/transmision.txt";
			break;
		case 2:
			var tree = "featureTree/equipamiento.txt";
			break;
		case 3:
			var tree = "featureTree/seguridad.txt";
			break;
		case 4:
			var tree = "featureTree/trenMotriz.txt";
			break;
		case 5:
			var tree = "featureTree/dimensiones.txt";
			break;
		case 6:
			var tree = "featureTree/desempeño.txt";
			break;
	}
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200) 
		{
			$("#tree").append("<textarea name='jsonObject' class='hidden' data-count='0'>" +this.responseText + "</textarea>");
			getFeatures(325);
		}
	};
	xhttp.open("GET", tree, true);
	xhttp.send();
}

loadTreeFeature();

function preguntaTipo(tipo)
{
	$("#InsertNewData").append("<h3>Acerca de " + tipo[0]+ "</h3>");
	var i=1;
	var tipos ="";
	while(i<tipo.length)
	{
		tipos= tipos +" "+ tipo[i];
		i++;
	}
	$("#InsertNewData").append("<h5>"+tipos+"</h5>");
}

function goOneWay(jsonData, tituloPregunta)
{
	var dato = {};
	var size = Math.floor(Math.random() * Object.keys(jsonData).length);
	var count = 0;
	Object.keys(jsonData).forEach(function(k){
		if(count == size)	
		{
		    if(typeof(jsonData[k])==='object')
		    {
		    	tituloPregunta.push(k);
	    		dato[k]= goOneWay(jsonData[k], tituloPregunta);
		   	}
		   	else
		   	{
		   		preguntaTipo(tituloPregunta);
		   		if(jsonData.Unidades)
		   		{
		   			getLastValue("Unidades", jsonData.Unidades);
		   		}
		   		else
		   		{
		   			getLastValue(k, jsonData[k]);
		   		}
		   		dato = "null";
		   	}
	   	}
	    count++;
	}, dato, size, tituloPregunta);
	
	return dato;
}

function goOneWayTo(jsonData, jsonResponse ,tituloPregunta)
{
	var dato = {};
	var count = 0;
	brancheDoIt = false;
	Object.keys(jsonResponse).every(function(k)
	{		
		//alert(JSON.stringify(jsonData));
	    if(typeof(jsonResponse[k])==='object' && typeof(jsonData[k])==='object')
	    {
	    	
	    	tituloPregunta.push(k);

    		dato[k]= goOneWayTo(jsonData[k], jsonResponse[k], tituloPregunta);
    		
	   	}
	   	else
	   	{
	   		i = 0;
	   		do{
	   			if(Object.keys(jsonResponse).includes(Object.keys(jsonData)[i]))
	   			{
	   				i++;
	   			}
	   			else
	   			{
	   				secondKey = Object.keys(jsonData)[i];
	   				tituloPregunta.push(secondKey);
					preguntaTipo(tituloPregunta);
					if(jsonData.Unidades)
			   		{
			   			getLastValue("Unidades", jsonData[secondKey].Unidades);
			   		}
			   		else
			   		{
			   			getLastValue("inputType", jsonData[secondKey].inputType);

			   		}
			   		dato[secondKey]= "null";
	   				break;
	   			}
	   			
	   		}
	   		while(i < Object.keys(jsonData).length);
	   		//alert(i + "  " + Object.keys(jsonData).length)
	   		if(i == Object.keys(jsonData).length)
	   		{
	   			brancheDoIt = true;
	   			alert(JSON.stringify(jsonData));
	   		}
	   	}  
	   	if(brancheDoIt)
	   	{
	   		dato[k] = jsonData[k];
	   	}	
	    count++;
	}, dato, tituloPregunta, brancheDoIt);
	return dato;
}

function sendData(json,versionID, url, lastVal) {
	var xhttp = new XMLHttpRequest();
	var urlPost = "/ayudanos/saveData/"+url;
	xhttp.open("POST", urlPost, true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200) 
		{
			partOfTree(this.responseText);
		}
	}
	xhttp.send("json_way="+json+"&versionID="+versionID+"&lastVal="+lastVal);
}

function agregar(valor)
{
	
	//alert("agrengando " + valor.data("type"));
	var $textArea = $("#InsertNewData").find("textarea");
	json = $textArea.val();
	if(valor.data("type")=="on")
	{
		if(valor.data("valor") == "1")
		{
			sendData(json, 325, "typeClikOn.php", true);
		}
		else if(valor.data("valor") == "0")
		{
			sendData(json, 325, "typeClikOn.php", false);
		}
		
	}
	else if(valor.data("type")=="Si")
	{
		if(valor.data("valor") == "1")
		{
			sendData(json, 325, "typeRadioClik.php", true);
		}
		else if(valor.data("valor") == "0")
		{
			sendData(json, 325, "typeRadioClik.php", false);
		}
	}
	else if(valor.data("type")=="num")
	{
		var num = {};
		num["data"] = $("input[name='num']").val();
		//alert($("input[name='num']").val());
		if(num["data"] != "")
		{
			sendData(json, 325, "typeNumTextClik.php", JSON.stringify(num));
		}
		else
		{
			$("input[name='num']").addClass("alert-danger");
			return false;
		}
	}
	else if(valor.data("type")=="unidades")
	{
		var num = {};
		num["unidades"] = $("input[name='num']").val();
		num["tipoUnidad"] = valor.data("unidades");
		if(num["unidades"]!= "")
		{
			sendData(json, 325, "typeNumTextClik.php", JSON.stringify(num));
		}
		else
		{
			$("input[name='num']").addClass("alert-danger");
			return false;
		}
	}
	else if(valor.data("type")=="text")
	{
		var data = {};
		data["data"] = $("input[name='text']").val();
		if(data["data"] != "")
		{
			sendData(json, 325, "typeNumTextClik.php", JSON.stringify(data));
		}
		else
		{
			$("input[name='text']").addClass("alert-danger");
			return false;
		}
	}
	
}
function getLastValue(key, inputVal)
{
	if(key === "inputType")
	{
		switch (inputVal) 
		{
			case "on":
				$("#InsertNewData").append("<button type='button' onclick='partOfTree()' class='btn btn-danger'>No</button> <button  type='button' class='btn info' data-valor='0' data-type='on' onclick='agregar($(this))'>Ni idea</button> <button class='btn btn-success' data-valor='1' data-type='on' onclick='agregar($(this))'> Si </button>");
				break;
			case "Si":
				$("#InsertNewData").append("<button type='button' onclick='partOfTree()' class='btn btn-danger'>No</button> <button  type='button'  data-valor='0' data-type='Si' onclick='agregar($(this))' class='btn info'>Ni idea</button> <button class='btn btn-success' data-valor='1' data-type='Si' onclick='agregar($(this))'> Si </button>");
				break;
			case "1":
				$("#InsertNewData").append("<div class='row'><div class='col-md-offset-4 col-md-4 col-md-offset-4'><input placeholder='Cantidad' class='form-control' name='num' type='number'></div></div><button onclick='partOfTree()' type='button' class='btn info'>Ni idea</button> <button class='btn btn-success' data-type='num' onclick='agregar($(this))'> Si </button>");
				break;
			case "text":
				$("#InsertNewData").append("<div class='row'><div class='col-md-offset-4 col-md-4 col-md-offset-4'><input  class='form-control' name='text' type='text'></div></div><button onclick='partOfTree()' type='button' class='btn info'>Ni idea</button> <button class='btn btn-success' data-type='text' onclick='agregar($(this))'> Si </button>");
				break;
		}
	}
	else if (key === "Unidades")
	{
		getUnidades(inputVal);
	}
}

function getUnidades(idUnidad)
{
	var xhttp = new XMLHttpRequest();
	var urlPost = "/ayudanos/unidades.php";
	xhttp.open("POST", urlPost, true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200) 
		{
			 var unidades = JSON.parse(this.responseText);
			$("#InsertNewData").append("<div class='row'><div class='col-md-offset-4 col-md-4 col-md-offset-4'><input class='form-control' name='num' type='number' placeholder='"+unidades.nombre+" (" + unidades.simbolo+")'></div></div><button onclick='loadTreeFeature()' type='button' class='btn info'>Ni idea</button> <button class='btn btn-success' data-type='unidades' data-unidades='"+unidades.simbolo+"' onclick='agregar($(this))'> Si </button>");
		}
	}
	xhttp.send("unidad="+idUnidad);
}

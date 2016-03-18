function listbox_move_all(sourceID, destID, flg, valueID) {
	var src = document.getElementById(sourceID);
	var dest = document.getElementById(destID);

	for (var count = src.options.length - 1; count >= 0; count--) {
		var option = src.options[count];
		var newOption = document.createElement("option");
		newOption.value = option.value;
		newOption.text = option.text;
		//newOption.selected = true;

		if (finditem(destID, newOption.value))
		{
			continue;
		}

		try {
			dest.add(newOption, null);
			option.remove();
		}
		catch (error) {
			dest.add(newOption);
			option.remove();
		}
	}

	sortlist(destID);
	if(flg == 1){
		set_category_ids(destID, valueID);
	}else if(flg == 0){
		set_category_ids(sourceID, valueID);
	}
}

function listbox_move(sourceID, destID, flg, valueID) {
	var src = document.getElementById(sourceID);
	var dest = document.getElementById(destID);

	var picked1 = false;
	for (var count = 0; count < src.options.length; count++) {
		if (src.options[count].selected == true) {
			picked1 = true;
		}
	}

	if (picked1 == false) {
		alert("Please select a category to move.");
		return;
	}

	for (var count = 0; count < src.options.length; count++) {
		if (src.options[count].selected == true) {
			var option = src.options[count];
			var newOption = document.createElement("option");
			newOption.value = option.value;
			newOption.text = option.text;
			//newOption.selected = true;

			if (finditem(destID, newOption.value))
			{
				//alert("Already added: " + newOption.text);
				continue;
				//break;
			}

			try {
				dest.add(newOption, null);
				option.remove();
				//alert("Added: " + newOption.text);
				//src.remove(count, null);
				continue;
				//break;
			}
			catch (error) {
				dest.add(newOption);
				option.remove();
				//src.remove(count);
				continue;
				//break;
			}

			count--;
		}
	}

	sortlist(destID);
	if(flg == 1){
		set_category_ids(destID, valueID);
	}else{
		set_category_ids(sourceID, valueID);
	}
	
}


function finditem(listID, selValue) {
	var listbox = document.getElementById(listID);
	//arrTexts = new Array();
	arrValues = new Array();

	for (var i = 0; i < listbox.length; i++) {
		arrValues[i] = listbox.options[i].value;
	}

	var pos = $.inArray(selValue, arrValues);
	if (pos < 0)
	{
		return false;
	}

	return true;
}

function sortlist(listID) {
	var listbox = document.getElementById(listID);
	arrValues = new Array();
	arrTexts = new Array();
	arryTextsTemp = new Array();

	for (var i = 0; i < listbox.length; i++) {
		arrValues[i] = listbox.options[i].value;
		arrTexts[i] = listbox.options[i].text;
		arryTextsTemp[i] = listbox.options[i].text;
	}

	arrTexts.sort();

	var pos;
	for (var i = 0; i < listbox.length; i++) {
		// Set text
		listbox.options[i].text = arrTexts[i];
		// Set value
		pos = $.inArray(arrTexts[i], arryTextsTemp);
		listbox.options[i].value = arrValues[pos];
	}
}

function set_category_ids(listID, valueID)
{
	var category_ids = "";
	var listbox = document.getElementById(listID);

	for (var count = 0; count < listbox.options.length; count++) {
		category_ids += listbox.options[count].value + "|";
	}

	if(category_ids != "") {
		category_ids = "|" + category_ids;
	}
	
	document.getElementById(valueID).value = category_ids;
}

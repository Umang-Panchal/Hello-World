//funtion for unit  onchange in imperial to metric
function calculate_metric_amount_update(recipe_parentClass,recipe_childClass){
    var calc_temp_metric_liquid=0;
    $(recipe_parentClass).find(recipe_childClass).each(function(){
        var type = $(this).find('span.measure-value').attr('data-type');
        var constant = $(this).find('span.measure-value').attr('data-constant');
        var quantity = $(this).find('span.measure-value').attr('data-original');
        var servings = $(this).find('span.measure-value').attr('data-servings');
        // console.log("servings",servings);
        // var updatedServing = $(this).find('span.measure-value').attr('data-servings');
        var updatedServing=$(this).closest('.sl_amount_update').find('.serving_box_new').find('.serving_btn_new').val();
        // console.log("updatedServing updatedServing",updatedServing);
        var gmweight = $(this).find('span.measure-value').attr('data-gmweight');
        if (constant == 8) {
            var value = (quantity * updatedServing) / servings;
            $(this).find('span.measure-value').text(toFraction(value));
            $(this).find('span.measure-value').attr('data-quantity', value);
            $(this).find('span.measure_unit').hide();
        } else {
            var calculation = (updatedServing * gmweight * quantity) / servings;
            convertToGram(type, calculation, $(this));
            $(this).find('span.measure_unit').show();
        }
        calc_temp_metric_liquid=0;
        calc_obj_temp=calc_for_metric_amount(type,constant, quantity, servings,updatedServing);
        var temp_metric=calc_for_liquid();
        let result_metric= temp_metric * 29.5735;
        calc_temp_metric_liquid = parseFloat(result_metric) + parseFloat(calc_temp_metric_liquid);
        $(this).find('span.measure-value').attr('data-amount_value',JSON.stringify(calc_temp_metric_liquid));
    });
    return calc_temp_metric_liquid;
}
function calculate_imperial_amount_update(recipe_imperial_parentClass,recipe_imperial_childClass){
    var calc_temp_imperial_liquid=0;
    $(recipe_imperial_parentClass).find(recipe_imperial_childClass).each(function(){
        var type = $(this).find('span.measure-value').attr('data-type');
        var constant = $(this).find('span.measure-value').attr('data-constant');
        var quantity = $(this).find('span.measure-value').attr('data-original');
        var servings = $(this).find('span.measure-value').attr('data-servings');
        // var updatedServing = $(this).find('span.measure-value').attr('data-servings');
        var updatedServing=$(this).closest('.sl_amount_update').find('.serving_box_new').find('.serving_btn_new').val();
        var value = (quantity * updatedServing) / servings;
        console.log('value',value);
        var arrayValue = [6, 7, 8, 9];
        var nextValue = 0;
        if(updatedServing != servings){
            var nextValue = (quantity * parseInt(servings) + 1) / servings;
        }
        if (!arrayValue.includes(parseInt(constant))) {
            if(constant==2){
                var calculation = (type == 1) ? convertSolidNew(constant - 2, value,nextValue) : convertLiquidNew(constant - 1, value,nextValue);
            }else{
                var calculation = (type == 1) ? convertSolidNew(constant - 2, value,nextValue) : convertLiquidNew(constant - 1, value,nextValue);
            }
            //old
            //var calculation = (type == 1) ? convertSolid(constant - 2, value) : convertLiquid(constant - 1, value);

            if (typeof calculation != 'undefined') {
                var cal_string = '';
                if (Object.keys(calculation).length >= 1) {
                    let is_zero =0;
                    let no_pinch =0;
                    $.each(calculation, function (key, value) {
                        console.log('cp:'+key);
                        //var frac_change = (key == 'c.') ? toFraction(value, 'true') : toFraction(value);
                        var frac_change = (key == 'c.') ? decimalToFraction(value) : decimalToFraction(value);
                        if(no_pinch == 1 && key == 'pinch'){ return false; }
                        if((value>=1 && key=='t.') || (value!=0 && key!='t.')){
                            no_pinch = 1;
                        }
                        if(value!=0){
                            //if(value>=1){no_pinch = 1}
                            is_zero=1;
                            cal_string += frac_change + ' ' + key + ' + ';
                        }
                    });
                    cal_string = cal_string.substring(0, cal_string.length - 2);
                    if(is_zero==0){
                        cal_string="NA ";
                    }
                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(cal_string);
                } else {
                    $(this).find('span.measure_unit').show();
                    $(this).find('span.measure-value').text('1');
                }
            }
            $(this).find('span.measure-value').attr('data-quantity', value);
        } else {
            if (constant == 6 && value >= 16) {
                // var ounce_result = value / 16;
                // var calculation = toFraction(ounce_result);
                var calculation = ounceConversion(value);

                $(this).find('span.measure_unit').hide();
                $(this).find('span.measure-value').text(calculation);
            } else if (constant == 6 && value < 16) {
                // var calculation = (parseFloat(value) > 0.5) ? Math.round(value) : 1;
                var ounce_result = (parseFloat(value) > 0.125) ? value : 0.125;
                var calculation = toFraction(ounce_result);

                $(this).find('span.measure_unit').hide();
                $(this).find('span.measure-value').text(calculation + ' oz.');
            } else if (constant == 7 && value < 1) {
                var calculation = toFraction(value * 16);

                $(this).find('span.measure_unit').hide();
                $(this).find('span.measure-value').text(calculation + ' oz.');
            } else if (constant == 7 && value >= 1) {
                // var calculation = toFraction(value);
                var calculation = poundConversion(value);

                $(this).find('span.measure_unit').hide();
                $(this).find('span.measure-value').text(calculation);
            } else if (constant == 9) {
                var fluid_ounce = (parseFloat(value) > 0.25) ? value : 0.25;
                var calculation = toFraction(fluid_ounce, 'true');

                $(this).find('span.measure_unit').hide();
                $(this).find('span.measure-value').text(calculation + ' fl-oz.');
            } else {
                var calculation = toFraction(value);

                $(this).find('span.measure_unit').hide();
                $(this).find('span.measure-value').text(calculation);
            }
            $(this).find('span.measure-value').attr('data-quantity', calculation);
        }
        calc_obj_temp=calculation;
        var temp=calc_for_liquid();
        calc_temp_imperial_liquid = parseFloat(temp) + parseFloat(calc_temp_imperial_liquid);
        console.log("calc_obj_temp_calc",calculation);
        $(this).find('span.measure-value').attr('data-amount_value',JSON.stringify(calculation));
    });
    return calc_temp_imperial_liquid;
}

//for amount update on document ready
function calculation_amount_update(unit,id,parentClass,childClass){
    var calc_temp_liquid=0;
    $(parentClass+id).find(childClass).each(function () {
        var type = $(this).find('span.measure-value').attr('data-type');
        var constant = $(this).find('span.measure-value').attr('data-constant');
        var quantity = $(this).find('span.measure-value').attr('data-original');
        var servings = $(this).find('span.measure-value').attr('data-servings');
        // var updatedServing = $(this).find('span.measure-value').attr('data-servings');
        var updatedServing=$(this).closest('.sl_amount_update').find('.serving_box_new').find('.serving_btn_new').val();
        console.log("type constant quantity servings",type,constant,quantity,servings,updatedServing);
        if (unit != 'metric') {
            // console.log("unit metric",unit);
            var value = (quantity * updatedServing) / servings;
            var arrayValue = [6, 7, 8, 9];
            var nextValue = 0;
            if(updatedServing != servings){
                var nextValue = (quantity * parseInt(servings) + 1) / servings;
            }
            if (!arrayValue.includes(parseInt(constant))) {
                if(constant==2){
                    var calculation = (type == 1) ? convertSolidNew(constant - 2, value,nextValue) : convertLiquidNew(constant - 1, value,nextValue);
                }else{
                    var calculation = (type == 1) ? convertSolidNew(constant - 2, value,nextValue) : convertLiquidNew(constant - 1, value,nextValue);
                }
                console.log("calculationdocs original",calculation);
                //old
                //var calculation = (type == 1) ? convertSolid(constant - 2, value) : convertLiquid(constant - 1, value);

                if (typeof calculation != 'undefined') {
                    var cal_string = '';
                    if (Object.keys(calculation).length >= 1) {
                        let is_zero =0;
                        let no_pinch =0;
                        $.each(calculation, function (key, value) {
                            console.log('cp:'+key);
                            //var frac_change = (key == 'c.') ? toFraction(value, 'true') : toFraction(value);
                            var frac_change = (key == 'c.') ? decimalToFraction(value) : decimalToFraction(value);
                            if(no_pinch == 1 && key == 'pinch'){ return false; }
                            if((value>=1 && key=='t.') || (value!=0 && key!='t.')){
                                no_pinch = 1;
                            }
                            if(value!=0){
                                //if(value>=1){no_pinch = 1}
                                is_zero=1;
                                cal_string += frac_change + ' ' + key + ' + ';
                            }
                        });
                        cal_string = cal_string.substring(0, cal_string.length - 2);
                        if(is_zero==0){
                            cal_string="NA ";
                        }
                        $(this).find('span.measure_unit').hide();
                        $(this).find('span.measure-value').text(cal_string);
                    } else {
                        $(this).find('span.measure_unit').show();
                        $(this).find('span.measure-value').text('1');
                    }
                }

                $(this).find('span.measure-value').attr('data-quantity', value);
            } else {
                if (constant == 6 && value >= 16) {
                    // var ounce_result = value / 16;
                    // var calculation = toFraction(ounce_result);
                    var calculation = ounceConversion(value);
                    console.log("calcualtion",calculation);
                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation);
                } else if (constant == 6 && value < 16) {
                    // var calculation = (parseFloat(value) > 0.5) ? Math.round(value) : 1;
                    var ounce_result = (parseFloat(value) > 0.125) ? value : 0.125;
                    var calculation = toFraction(ounce_result);
                    console.log("calcualtion",calculation);
                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation + ' oz.');
                } else if (constant == 7 && value < 1) {

                    var calculation = toFraction(value * 16);
                    console.log("calcualtion",calculation);
                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation + ' oz.');
                } else if (constant == 7 && value >= 1) {
                    // var calculation = toFraction(value);
                    var calculation = poundConversion(value);
                    console.log("calcualtion",calculation);
                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation);
                } else if (constant == 9) {
                    var fluid_ounce = (parseFloat(value) > 0.25) ? value : 0.25;
                    var calculation = toFraction(fluid_ounce, 'true');
                    console.log("calcualtion",calculation);
                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation + ' fl-oz.');
                } else {
                    var calculation = toFraction(value);
                    console.log("calcualtion",calculation);
                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation);
                }
                $(this).find('span.measure-value').attr('data-quantity', calculation);
            }
        } else {
            var gmweight = $(this).find('span.measure-value').attr('data-gmweight');
            if (constant == 8) {
                var value = (quantity * updatedServing) / servings;
                $(this).find('span.measure-value').text(toFraction(value));
                $(this).find('span.measure-value').attr('data-quantity', value);
                $(this).find('span.measure_unit').hide();
                // console.log("constant==8");
                $(this).find('span.measure-value').attr('data-quantity', value);

            } else {
                var calculation = (updatedServing * gmweight * quantity) / servings;
                convertToGram(type, calculation, $(this));
                $(this).find('span.measure_unit').show();
            }
            $(this).find('span.measure-value').attr('data-quantity',  (quantity * updatedServing) / servings);
        }
        // $(this).find('span.measure-value').attr('data-amount_value',JSON.stringify(calculation));
        if(unit == "imperial"){
            calc_temp_liquid=0;
            calc_obj_temp=calculation;
            var temp=calc_for_liquid();
            calc_temp_liquid = parseFloat(temp) + parseFloat(calc_temp_liquid);
        }else{
            calc_temp_liquid=0;
            calc_obj_temp=calc_for_metric_amount(type,constant, quantity, servings,updatedServing);
            // alert("calc_obj_temp"+JSON.stringify(calc_obj_temp));
            var temp_metric=calc_for_liquid();
            let result_metric= temp_metric * 29.5735;
            calc_temp_liquid = parseFloat(result_metric) + parseFloat(calc_temp_liquid);
            calculation=calc_temp_liquid;
        }
        $(this).find('span.measure-value').attr('data-amount_value',JSON.stringify(calc_temp_liquid));
    });
    return calc_temp_liquid;
}

//function for amount update on add,sub and keyup of serving..
function calculation_serving_update(updatedServing,defaultIngCLS='.recipe_new_servings') {
    var calc_temp_liquid=0;
    $(defaultIngCLS).each(function () {
        // var newData = '';
        // var is_fraction = $(this).find('span.measure-value').attr('data-fraction');
        var unit = $(this).find('span.measure-value').attr('data-unit');
        var type = $(this).find('span.measure-value').attr('data-type');
        var constant = $(this).find('span.measure-value').attr('data-constant');
        var quantity = $(this).find('span.measure-value').attr('data-original');
        var servings=toDecimal($(this).find('span.measure-value').attr('data-servings'));
        // console.log("unit,type,constant,quantity,servnigs,updatedServing",unit,type,constant,quantity,servings,updatedServing);
        if (unit != 'metric') {
            console.log("unit unit",unit);
            var value = (quantity * updatedServing) / servings;
            var arrayValue = [6, 7, 8, 9];
            var nextValue = 0;
            if(updatedServing != servings){
                var nextValue = (quantity * parseInt(updatedServing) + 1) / servings;
            }
            console.log("type type 123",type);
            if (!arrayValue.includes(parseInt(constant))) {
                if(constant==2){
                    var calculation = (type == 1) ? convertSolidNew(constant - 2, value,nextValue) : convertLiquidNew(constant - 1, value,nextValue);
                }else{
                    var calculation = (type == 1) ? convertSolidNew(constant - 2, value,nextValue) : convertLiquidNew(constant - 1, value,nextValue);
                }
                //old
                //var calculation = (type == 1) ? convertSolid(constant - 2, value) : convertLiquid(constant - 1, value);

                if (typeof calculation != 'undefined') {
                    var cal_string = '';
                    if (Object.keys(calculation).length >= 1) {
                        let is_zero =0;
                        let no_pinch =0;
                        $.each(calculation, function (key, value) {
                            // console.log('cp:'+key);
                            //var frac_change = (key == 'c.') ? toFraction(value, 'true') : toFraction(value);
                            var frac_change = (key == 'c.') ? decimalToFraction(value) : decimalToFraction(value);
                            if(no_pinch == 1 && key == 'pinch'){ return false; }
                            if((value>=1 && key=='t.') || (value!=0 && key!='t.')){
                                no_pinch = 1;
                            }
                            if(value!=0){
                                //if(value>=1){no_pinch = 1}
                                is_zero=1;
                                cal_string += frac_change + ' ' + key + ' + ';
                            }
                        });
                        cal_string = cal_string.substring(0, cal_string.length - 2);
                        if(is_zero==0){
                            cal_string="NA ";
                        }
                        $(this).find('span.measure_unit').hide();
                        $(this).find('span.measure-value').text(cal_string);
                    } else {
                        $(this).find('span.measure_unit').show();
                        $(this).find('span.measure-value').text('1');
                    }
                }
                $(this).find('span.measure-value').attr('data-quantity', value);
            } else {
                if (constant == 6 && value >= 16) {
                    // var ounce_result = value / 16;
                    // var calculation = toFraction(ounce_result);
                    var calculation = ounceConversion(value);
                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation);
                } else if (constant == 6 && value < 16) {
                    // var calculation = (parseFloat(value) > 0.5) ? Math.round(value) : 1;
                    var ounce_result = (parseFloat(value) > 0.125) ? value : 0.125;
                    var calculation = toFraction(ounce_result);

                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation + ' oz.');
                } else if (constant == 7 && value < 1) {
                    var calculation = toFraction(value * 16);

                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation + ' oz.');
                } else if (constant == 7 && value >= 1) {
                    // var calculation = toFraction(value);
                    var calculation = poundConversion(value);

                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation);
                } else if (constant == 9) {
                    var fluid_ounce = (parseFloat(value) > 0.25) ? value : 0.25;
                    var calculation = toFraction(fluid_ounce, 'true');

                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation + ' fl-oz.');
                } else {
                    var calculation = toFraction(value);
                    $(this).find('span.measure_unit').hide();
                    $(this).find('span.measure-value').text(calculation);
                }
                $(this).find('span.measure-value').attr('data-quantity', calculation);
            }
        } else {
            var gmweight = $(this).find('span.measure-value').attr('data-gmweight');
            console.log("gmweight gmweight"+gmweight);
            if (constant == 8) {
                var value = (quantity * updatedServing) / servings;
                $(this).find('span.measure-value').text(toFraction(value));
                $(this).find('span.measure-value').attr('data-quantity', value);
                $(this).find('span.measure_unit').hide();
            } else {
                var calculation = (updatedServing * gmweight * quantity) / servings;
                console.log("calculation", calculation);
                convertToGram(type, calculation, $(this));
                $(this).find('span.measure_unit').show();
            }
            $(this).find('span.measure-value').attr('data-quantity',  (quantity * updatedServing) / servings);
        }
        // $(this).find('span.measure-value').attr('data-amount_value',JSON.stringify(calculation));
        if(unit == "imperial"){
            calc_obj_temp=0;
            calc_obj_temp=calculation;
            var temp=calc_for_liquid();
            calc_temp_liquid = parseFloat(temp) + parseFloat(calc_temp_liquid);
        }else{
            calc_obj_temp=0;
            calc_obj_temp=calc_for_metric_amount(type,constant, quantity, servings,updatedServing);
            // alert("calc_add_obj"+JSON.stringify(calc_obj_temp));
            var temp_metric=calc_for_liquid();
            let result_metric= temp_metric * 29.5735;
            calc_temp_liquid = parseFloat(result_metric) + parseFloat(calc_temp_liquid);
            calculation=calc_temp_liquid;
            // $(this).find('span.measure-value').attr('data-amount_value',JSON.stringify(calc_temp_liquid));
        }
        $(this).find('span.measure-value').attr('data-amount_value',JSON.stringify(calculation));
        // $('.recipeIngredientsServings').find('span.measure-value').attr('data-amount_value',JSON.stringify(calculation));
    });
    return calc_temp_liquid;
}

//function for imperial to metric calc of according to object/
function calc_for_metric_amount(type,constant, quantity, servings,updatedServing){
    let unit="imperial";
    if (unit != 'metric') {
        // console.log("unit metric",unit);
        var value = (quantity * updatedServing) / servings;
        var arrayValue = [6, 7, 8, 9];
        var nextValue = 0;
        if(updatedServing != servings){
            var nextValue = (quantity * parseInt(servings) + 1) / servings;
        }
        if (!arrayValue.includes(parseInt(constant))) {
            if(constant==2){
                var calculation = (type == 1) ? convertSolidNew(constant - 2, value,nextValue) : convertLiquidNew(constant - 1, value,nextValue);
            }else{
                var calculation = (type == 1) ? convertSolidNew(constant - 2, value,nextValue) : convertLiquidNew(constant - 1, value,nextValue);
            }
            if (typeof calculation != 'undefined') {
                var cal_string = '';
                if (Object.keys(calculation).length >= 1) {
                    let is_zero =0;
                    let no_pinch =0;
                    $.each(calculation, function (key, value) {
                        console.log('cp:'+key);
                        //var frac_change = (key == 'c.') ? toFraction(value, 'true') : toFraction(value);
                        var frac_change = (key == 'c.') ? decimalToFraction(value) : decimalToFraction(value);
                        if(no_pinch == 1 && key == 'pinch'){ return false; }
                        if((value>=1 && key=='t.') || (value!=0 && key!='t.')){
                            no_pinch = 1;
                        }
                        if(value!=0){
                            //if(value>=1){no_pinch = 1}
                            is_zero=1;
                            cal_string += frac_change + ' ' + key + ' + ';
                        }
                    });
                    cal_string = cal_string.substring(0, cal_string.length - 2);
                    if(is_zero==0){
                        cal_string="NA ";
                    }
                }
            }
            //can be removable
            $(this).find('span.measure-value').attr('data-quantity', value);
        } else {
            if (constant == 6 && value >= 16) {
                var calculation = ounceConversion(value);
            } else if (constant == 6 && value < 16) {
                var ounce_result = (parseFloat(value) > 0.125) ? value : 0.125;
                var calculation = toFraction(ounce_result);
            } else if (constant == 7 && value < 1) {

                var calculation = toFraction(value * 16);
            } else if (constant == 7 && value >= 1) {
                var calculation = poundConversion(value);
            } else if (constant == 9) {
                var fluid_ounce = (parseFloat(value) > 0.25) ? value : 0.25;
                var calculation = toFraction(fluid_ounce, 'true');
            } else {
                var calculation = toFraction(value);
            }
            //can be  removable
            $(this).find('span.measure-value').attr('data-quantity', calculation);
        }
    }
    return calculation;
}
//calculation for liquid on add,sub,paste for metric calc.
function calc_amount_for_liquid(type, unit, constant, serving, that, flag) {
    //for liquid measure
    var calc_temp_liquid = 0;
    var updatedServing = serving.attr('data-new_serving');
    //original
    var quantity1 = that.find('span.measure-value').attr('data-quantity');
    var old_servings1 = serving.attr('data-old_serving');
    console.log("old_serving1", quantity1);
    // console.log("updatedQuantity",updatedServing,quantity1,old_servings1);
    // console.log("updatedServing quantity1 old_servings1",updatedServing);
    if (unit != 'metric') {
        var value = (quantity1 * updatedServing) / old_servings1;
        console.log("value value", value);
        var arrayValue = [6, 7, 8, 9];
        var nextValue = 0;
        if (updatedServing != old_servings1) {
            var nextValue = (quantity1 * parseInt(old_servings1) + 1) / old_servings1;
        }
        if (!arrayValue.includes(parseInt(constant))) {
            if (constant == 2) {
                var calculation = (type == 1) ? convertSolidNew(constant - 2, value, nextValue) : convertLiquidNew(constant - 1, value, nextValue);
            } else {
                var calculation = (type == 1) ? convertSolidNew(constant - 2, value, nextValue) : convertLiquidNew(constant - 1, value, nextValue);
            }
            console.log("calculationdocs", calculation);

            if (typeof calculation != 'undefined') {
                var cal_string = '';
                if (Object.keys(calculation).length >= 1) {
                    let is_zero = 0;
                    let no_pinch = 0;
                    $.each(calculation, function (key, value) {
                        console.log('cp:' + key);
                        //var frac_change = (key == 'c.') ? toFraction(value, 'true') : toFraction(value);
                        var frac_change = (key == 'c.') ? decimalToFraction(value) : decimalToFraction(value);
                        if (no_pinch == 1 && key == 'pinch') {
                            return false;
                        }
                        if ((value >= 1 && key == 't.') || (value != 0 && key != 't.')) {
                            no_pinch = 1;
                        }
                        if (value != 0) {
                            //if(value>=1){no_pinch = 1}
                            is_zero = 1;
                            cal_string += frac_change + ' ' + key + ' + ';
                        }
                    });
                    cal_string = cal_string.substring(0, cal_string.length - 2);
                    if (is_zero == 0) {
                        cal_string = "NA ";
                    }
                    // $(this).find('span.measure_unit').hide();
                    // $(this).find('span.measure-value').text(cal_string);
                } else {
                    // $(this).find('span.measure_unit').show();
                    // $(this).find('span.measure-value').text('1');
                }
            }

            // $(this).find('span.measure-value').attr('data-quantity', value);
        } else {
            if (constant == 6 && value >= 16) {
                var calculation = ounceConversion(value);
            } else if (constant == 6 && value < 16) {
                var ounce_result = (parseFloat(value) > 0.125) ? value : 0.125;
                var calculation = toFraction(ounce_result);
            } else if (constant == 7 && value < 1) {
                var calculation = toFraction(value * 16);
            } else if (constant == 7 && value >= 1) {
                var calculation = poundConversion(value);
            } else if (constant == 9) {
                var fluid_ounce = (parseFloat(value) > 0.25) ? value : 0.25;
                var calculation = toFraction(fluid_ounce, 'true');
            } else {
                var calculation = toFraction(value);
            }
        }
    } else {
        var gmweight1 = that.find('span.measure-value').attr('data-gram');
        if (constant == 8) {
            var value = (quantity1 * updatedServing) / old_servings1;
        } else {
            console.log("else metric log");
            var calculation = (updatedServing * gmweight1 * quantity1) / old_servings1;
            convertToGram(type, calculation, $(this));
            console.log("gmweight1 value", calculation);
            // $(this).find('span.measure_unit').show();
        }
    }
    calc_obj_temp = calculation;
    console.log("calc_obj_cal", calc_obj_temp);
    if (flag == 0) {
        let calc_obj_temp_metric = calc_for_metric_liquid();
        console.log("calc_obj_temp_metric", calc_obj_temp_metric);
        return calc_obj_temp_metric;
    } else {
        let calc_obj_temp_metric = calc_for_liquid();
        console.log("calc_obj_temp_metric" + calc_obj_temp_metric);
        calc_temp_liquid = parseFloat(calc_obj_temp_metric) + parseFloat(calc_temp_liquid);
        console.log("calc_temp_liquid", calc_temp_liquid);
        return calc_temp_liquid;
    }
    //end
}
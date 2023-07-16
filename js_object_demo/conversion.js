function toDecimal(fraction) {
    var result, wholeNum = 0, frac, deci = 0;
    if (fraction.search('/') >= 0) {
        if (fraction.search(' ') >= 0) {
            var wholeNum = fraction.split(' ');
            frac = wholeNum[1];
            wholeNum = parseInt(wholeNum, 10);
        } else {
            frac = fraction;
        }
        if (fraction.search('/') >= 0) {
            frac = frac.split('/');
            deci = parseInt(frac[0], 10) / parseInt(frac[1], 10);
        }
        result = wholeNum + deci;
        return Math.round(result);
    } else {
        result = +fraction;
        return Math.round(result);
    }
}
function NA(){
    // return '<span data-toggle="tooltip" data-placement="top" data-original-title="This ingredient measurements cannot go any lower. Scaling down further will remove this ingredient"><p style="display: inline-block;"><span style="display: inline-block;">--</span></p></span>';
    return '--';
}
function toFraction(float, type='false') {
    var whole = Math.floor(float);
    var decimal = float - whole;
    if (type == 'true') {
        var leastCommonDenom = 4;
        var denominators = [2, 3, 4];
    } else {
        var leastCommonDenom = 8;
        var denominators = [2, 3, 4, 8];
    }
    var roundedDecimal = Math.round(decimal * leastCommonDenom) / leastCommonDenom;
    console.log('roundDecimal',roundedDecimal);

    roundedDecimal = (roundedDecimal);
    if (roundedDecimal == 0)
        return whole;
    if (roundedDecimal == 1)
        return whole + 1;

    $.each(denominators, function (key, value) {
        if (roundedDecimal * value == Math.floor(roundedDecimal * value)) {
            denom = value;
            return false;
        }
    });
    console.log('denom',denom);
    if (whole == 0) {
        return (roundedDecimal * denom) + "/" + denom;
    } else {
        console.log('each',whole + " " + (roundedDecimal * denom) + "/" + denom);
        return whole + " " + (roundedDecimal * denom) + "/" + denom;
    }
}

function decimalToFraction(value,key = '') {
    var num =value
        , rounded = Math.floor(value)
        , rest  = num - Math.floor(value);
    if(rest.toString().length > 5){
        //console.log('rounding: '+rest.toString().length);
        rest= (Math.round((rest + Number.EPSILON) * 100) / 100);
    }

    if(rest!=0){
        console.log('rest: '+rest+',fac:'+fractionFromDecimal[rest]);
        var string = '';
        if(fractionFromDecimal[rest] != undefined){
            string = fractionFromDecimal[rest];
        } else {
            rest = rest - 0.125;
            if(key != ''){
                string = fractionFromDecimal[rest]+' '+key;
            } else {
                string = fractionFromDecimal[rest];
            }
            rest = 0.125;
            string = string + ' + ' + fractionFromDecimal[rest];


        }
        return cal_string = (rounded!=0)?(rounded + ' ' + string):string;
    }else{
        return cal_string = (rounded!=0)?rounded:'';
    }

}

function convertToGram(type, total, that) {
    // console.log("totla",total);
    if (type == 1) {
        if (total >= 1000) {
            total = (total / 1000).toFixed(1);
            that.find('span.measure_unit').text('kg');
            that.find('span.measure-value').text(total);
        } else {
            // total = toDecimal(total.toString());
            total = total.toFixed(1);
            if(total % 1 === 0){
                total = Math.round(total);
            }
            if(total == 0){
                total = NA();
                that.find('span.removeIngredient').show();
            } else {
                that.find('span.removeIngredient').hide();
            }

            that.find('span.measure_unit').text('grams');
            that.find('span.measure-value').text(total);
        }
    }
    if (type == 2) {
        if (total >= 1000) {
            total = (total / 1000).toFixed(1);
            that.find('span.measure_unit').text('l');
            that.find('span.measure-value').text(total);
        } else {
            // total = toDecimal(total.toString());
            total = total.toFixed(1);
            if(total % 1 === 0){
                total = Math.round(total);
            }
            if(total == 0){
                total = NA();
                that.find('span.removeIngredient').show();
            } else {
                that.find('span.removeIngredient').hide();
            }
            that.find('span.measure_unit').text('ml');
            that.find('span.measure-value').text(total);
        }
    }
}

function poundConversion(x) {
    var y1 = parseFloat(x) * 16;
    var y2 = Math.floor(y1 / 16);
    var y3 = (y1 - y2 * 16);
    var text = '';

    if (y2 > 0) {
        text += y2 + ' lb. + ';
    }

    if (y3 > 0) {
        text += Math.round(y3) + ' oz. + ';
    }
    text = text.substring(0, text.length - 2);
    return text;
}

function ounceConversion(x) {
    var y = parseFloat(x) / 16;
    var y2 = Math.floor(y);
    var y3 = (y-y2) * 16;
    console.log('c'+y);
    console.log('y2->'+y2);
    console.log('y3->'+y3);
    var text = '';
    if (y2 > 0) {
        text += y2 + ' lb. + ';
    }

    if (y3 > 0) {
        text += Math.round(y3) + ' oz. + ';
    }
    text =  text.substring(0, text.length - 2);
    return text;
}

// [1, 16, 64, 192, 221184],
// [1 / 16, 1, 16, 48, 1152],
// [1 / 64, 1 / 16, 1, 3, 24],
// [1 / 192, 1 / 64, 1 / 3, 1, 8],
// [1 / 221184, 1 / 1152, 1 / 24, 1 / 8, 1]

// [1, 16, 256, 768, 12288],
// [1 / 16, 1, 16, 48, 768],
// [1 / 256, 1 / 16, 1, 3, 48],
// [1 / 768, 1 / 48, 1 / 3, 1, 16],
// [1 / 12288, 1 / 768, 1 / 48, 1 / 16, 1]
// const quart = 0, cup = 1, tbsp = 2, tsp = 3, pinch = 4;

/* Conversion function for solid type */
const conversionSolid = [
    [1, 16, 48, 768 ],
    [1 / 16, 1, 3, 48],
    [1 / 48, 1 / 3, 1, 16],
    [1 / 768, 1 / 48, 1 / 16, 1]
];
const conversionSolidNew = [
    [1, 16, 48, 768 ],
    [1 / 16, 1, 3, 48],
    [1 / 48, 1 / 3, 1, 16],
    [1 / 768, 1 / 48, 1 / 16, 1]
];
const convertPinches = {
    //0: 12288,
    0: 768,
    1: 48,
    2: 16,
    3: 1,
};
const biasSolidNew = {
    // 0: "g.",
    0: "c.",
    1: "c.",
    2: "c.",
    3: "c.",
    4: "c.",
    5: "c.",
    6: "T.",
    7: "t.",
    8: "t.",
    9: "t.",
    10: "t.",
    11: "t.",
    12: "pinch",
};
const biasSolid = {
    0: "c.",
    1: "T.",
    2: "t.",
    3: "pinch"
};
let RangeSolid = {
    3: [0, 1],
    2: [0.125, 2.875],
    1: [1, 3],
    0: [0.25, 999]
};
let newRangeSolid = {
    3: [0, 2],
    2: [0.125, 3],
    1: [1, 4],
    0: [0.25, 999]
};

let convertIntoPinch = {
    // 0: 12288,
    0: 768,
    1: 576,
    2: 514.56,
    3: 384,
    4: 253.44,
    5: 192,
    6: 48,
    7: 16,
    8: 12,
    9: 8,
    10: 4,
    11: 2,
    12: 1,

};
let countPlus = {
    //0: 1,
    0: 1,
    1: 0.75,
    2: 0.67,
    3: 0.5,
    4: 0.33,
    5: 0.25,
    6: 1,
    7: 1,
    8: 0.75,
    9: 0.5,
    10: 0.25,
    11: 0.125,
    12: 1,

};
let countCupPlus = {
    0: 1,
    1: 1,
    0: 0.75,
    1: 0.67,
    2: 0.5,
    3: 0.33,
    4: 0.25,
    7: 1,
    8: 1,
    9: 0.75,
    10: 0.5,
    11: 0.25,
    12: 0.125,
    13: 1,

};
// let cupFraction = {
//     "0.75": 576,
//     "0.67": 514.56,
//     "0.5": 384,
//     "0.33": 253.44,
//     "0.25": 192,

// };

let fractionFromDecimal = {
    // "0.875": "7/8", // &frac78;
    "0.75": "3/4",
    "0.67": "2/3",
    "0.5": "1/2",
    "0.33": "1/3",
    "0.25": "1/4",
    "0.125": "1/8",
    // "0.375": "3/8",
    // "0.625": "5/8",
};
let teaspoonFraction = {
    // "0.875": 14,
    "0.75": 12,
    // "0.625": 10,
    "0.5": 8,
    // "0.375": 6,
    "0.25": 4,
    "0.125": 2,

};
const liquidLCD = {
    0: 1536,
    1: 384,
    2: 288,
    3: 256,
    4: 192,
    5: 128,
    6: 96,
    7: 24,
    8: 8,
    9: 6,
    10: 4,
    11: 2,
    12: 1,
};
const liquidUnitLCD = {
    0: 'qt.',
    1: 'c.',
    2: 'c.',
    3: 'c.',
    4: 'c.',
    5: 'c.',
    6: 'c.',
    7: 'T.',
    8: 't.',
    9: 't.',
    10: 't.',
    11: 't.',
    12: 't.'
};
const convertLiquidLCD = {
    0: 1536,
    1: 384,
    2: 24,
    3: 8,
};
let countPlusLiquid = {
    0: 1,
    1: 1,
    2: 0.75,
    3: 0.67,
    4: 0.5,
    5: 0.33,
    6: 0.25,
    7: 1,
    8: 1,
    9: 0.75,
    10: 0.5,
    11: 0.25,
    12: 0.125,

};
const liquidUnitFormat = {
    'qt.':32,
    'c.': 8,
    'T.':0.50,
    't.':0.166,
    'pinch':0.010,
};

const metricUnitFormat={
    'qt.':946.4,
    'c.': 236.6,
    'T.':14.787,
    't.':4.929,
    'pinch':0.31,
}
function checkUnitSolid(result, range, count) {
    const final = count;
    if (result - range >= 0) {
        count += range;
        return checkUnitSolid(result - range, range, count);
    } else {
        return final;
    }
}
function convertSolidNew(type, total, nextTotal = 0) {
    if (parseFloat(total).toFixed(2) === 0.0) {
        return 0;
    }
    // console.log("next total: "+nextTotal);
    // console.log("before total: "+total);

    // total= (Math.round((total + Number.EPSILON) * 100) / 100);

    // console.log("solid type: "+type);
    // console.log("total: ",total);
    let final_total=total;
    let tolerance_per=(2/100);

    //let answer=0;
    //let cupFrac='';
    //let solid_answer='';
    let final_result = {
        //'gal.': 0,
        'c.': 0,
        'T.': 0,
        't.': 0,
        'pinch': 0
    };

    final_total = total*convertPinches[type];
    total_pinches = final_total;
    next_total_pinches = 0;
    if(nextTotal != 0){
        next_total_pinches = nextTotal*convertPinches[type];
    }
    let tolerance=(Math.round(((final_total*tolerance_per) + Number.EPSILON) * 100) / 100);
    console.log('total pinches: '+final_total);
    // console.log('tolerance: '+tolerance);

    if(nextTotal != 0 && total_pinches == final_total){
        errorRata = final_total/next_total_pinches;
        if(errorRata <= 0.05){
            final_total = next_total_pinches;
        }
    }
    for ($i = 0; $i <= 12; $i++  ) {
        index = $i;

        if(final_total>=convertIntoPinch[index]){
            console.log('index =>' + index);
            if($i != 0 && nextTotal != 0 && total_pinches != final_total){
                errorRata = final_total/total_pinches;
                // console.log('aavyu :'+ final_total);
                console.log('errorRate :'+ errorRata);
                // console.log('index  :'+ index);
            if(errorRata <= 0.05){
                lastNearValueDiff = convertIntoPinch[index -1] - final_total;
                currentNearValueDiff = final_total - convertIntoPinch[index];
                console.log('lastValue => '+convertIntoPinch[index - 1]);
                console.log('lastNearValueDiff ->' + lastNearValueDiff);
                    console.log('currentNearValueDiff ->'+currentNearValueDiff);
                if(lastNearValueDiff < currentNearValueDiff){
                    if(convertIntoPinch[index - 1] > 15){
                        newErrorRate = convertIntoPinch[index - 1] / total_pinches;
                        if(newErrorRate <= 0.05){
                            if(convertIntoPinch[index - 1] == 768 || convertIntoPinch[index - 1] == 48 || convertIntoPinch[index - 1] == 16){
                                final_total = convertIntoPinch[index - 1];
                                $i = $i-1;
                                index = $i;
                            } else {
                                final_total = 0;
                            }
                        } else {
                            $i = $i-1;
                            index = $i;
                            console.log('lastPinch :'+index + '=>'+ convertIntoPinch[index]);
                            final_total = convertIntoPinch[index];
                        }
                    } else {
                        final_total = 0;
                    }
                } else {
                    if(convertIntoPinch[index] == 768 || convertIntoPinch[index] == 48 || convertIntoPinch[index] == 16){
                        final_total = convertIntoPinch[index];
                    } else {
                        final_total = 0;
                    }
                }

            }
        }
            // console.log('loop: '+countPlus[index]+' total: '+convertIntoPinch[index]);
            // console.log('ans: '+Math.abs(final_total));
            if(final_total != 0){
                // if($i != 11){
                    final_total=final_total-convertIntoPinch[index];
                    final_result[biasSolidNew[index]] = (final_result[biasSolidNew[index]] + countPlus[index]);
                    console.log('add => '+ biasSolidNew[index],countPlus[index]);
                    console.log(final_result);
                // }
            }
        // $i++;
        // console.log('final_result',final_result);
            if(Math.abs(final_total) >= convertIntoPinch[index] && final_total != 0){
                $i--;
            }
            // while(Math.abs(final_total)>=convertIntoPinch[index]){
            //     final_total=final_total-convertIntoPinch[index];
            //     final_result[biasSolidNew[index]] = (final_result[biasSolidNew[index]] + countPlus[index]);
            //     console.log('final_result',final_result);
            //     console.log('while_final_total',final_total);
            //     errorRata = final_total/total_pinches;
            //     // console.log('while_final:'+final_total);
            //     // console.log('while_total:'+total_pinches);
            //     console.log('while_error:'+errorRata);

            //     if($i != 0 && nextTotal != 0 && total_pinches != final_total){
            //         errorRata = final_total/total_pinches;
            //         if(errorRata <= 0.05 && final_total != 0){
            //             console.log('while_conver',convertIntoPinch[index - 1]);
            //             console.log('while_total',final_total);
            //             if(final_total >=convertIntoPinch[index - 1]){
            //                 $i = $i - 1;
            //                 index = $i;

            //                 final_total = convertIntoPinch[index];
            //                 console.log('finalTotalAfterErrorLog', final_total);
            //             }

            //         }
            //     }
            //     //final_result[$i] += final_result[$i] + 1;
            // }
            if(parseInt(final_total)>0.00){
                // final_result[biasSolidNew[16]]
            }
            // $(fractionFromDecimal).each(function( index , value) {

            //     while(final_total>=value){

            //         final_total=final_total-value;
            //        // cupFrac += cupFrac + ' + '+index;
            //         final_result[biasSolidNew[$i]] = final_result[biasSolidNew[$i]] + countCupPlus[$i];
            //     }

            // });
            //solid_answer += answer +  cupFrac + biasSolidNew[i]+' + ';
        }

        // solid_answer += answer +  biasSolidNew[i]+' + ';

    }
    if((final_total)>0.00){
        // console.log('adjust pnch: '+final_total);
        if(Math.round(final_total)>=1){
            // console.log('final  pnch: '+parseInt(Math.round(final_total)));

            // final_result[biasSolidNew[15]] += parseInt(Math.round(final_total));
        }

    }
    if(final_result['c.'] != 0 && final_result['t.']< 1){
        final_result['t.'] = 0;
    }
    if(final_result['t.'] != 0){
        // final_result['pinch'] = 0;
    }
    console.log("final_result final_result",final_result);
    return final_result;
    // for ($i = 0; $i < conversionSolidNew.length; $i++) {
    //     if (conversionSolidNew[$i][type] <= total) {
    //         result[biasSolidNew[$i]] = (result[biasSolidNew[$i]] || 0) + 1;
    //         convertSolidNew(type, total - conversionSolidNew[$i][type], result);
    //     }
    // }
    // if (!jQuery.isEmptyObject(result)) {
    //     return filterResultSolid(result);
    // }
}
function convertSolid(type, total, result = {}) {
    if (parseFloat(total).toFixed(2) === 0.0) {
        return 0;
    }
    //console.log(conversionSolid);
    console.log(total);
    for ($i = 0; $i < conversionSolid.length; $i++) {
        if (conversionSolid[$i][type] <= total) {
            result[biasSolid[$i]] = (result[biasSolid[$i]] || 0) + 1;
            convertSolid(type, total - conversionSolid[$i][type], result);
        }
    }
    if (!jQuery.isEmptyObject(result)) {
        return filterResultSolid(result);
    }
}
function filterResultSolidNew(result) {
    let final_result = {
        'qt.': 0,
        'c.': 0,
        'T.': 0,
        't.': 0,
        'pinch': 0
    };
    var i = 4;
    for (i; i >= 0; i--) {
        if (biasSolidNew[i] in result && result[biasSolidNew[i]] <= RangeSolid[i][1] &&  result[biasSolidNew[i]] >= RangeSolid[i][0]){
            final_result[biasSolidNew[i]] += result[biasSolidNew[i]]
        }
        else if (biasSolidNew[i] in result && result[biasSolidNew[i]] >= RangeSolid[i][1]) {
            // if (biasSolidNew[i] == "qt") {
            //     final_result[biasSolidNew[i]] += result[biasSolidNew[i]];
            // } else {
            let output = checkUnitSolid(result[biasSolidNew[i]], newRangeSolid[i][1], (count = 0)); // call recurssion function
            final_result[biasSolidNew[i]] = result[biasSolidNew[i]] - output; // subtract maximum range
            final_result[biasSolidNew[i - 1]] += conversionSolidNew[i][i - 1] * output; // convert subtracted value
            //     final_result[biasSolidNew[i - 1]] += conversionSolidNew[i][i - conversionSolidNew
            final_result[biasSolidNew[i+1]] += conversionSolidNew[i][i + 1] * result[biasSolidNew[i]]
        }
    }
    var count = 0;
    for (key in final_result) {
        if (final_result.hasOwnProperty(key) && final_result[key] == 0) {
            delete final_result[key];
            count += 1;
        }
    }
    for (key in final_result) {
        if (count < 3 && key == 'pinch') {
            delete final_result[key];
        }
    }
    return final_result
}
function filterResultSolid(result) {
    let final_result = {
        'c.': 0,
        'T.': 0,
        't.': 0,
        'pinch': 0
    };
    var i = 3;
    for (i; i >= 0; i--) {
        if (biasSolid[i] in result && result[biasSolid[i]] <= RangeSolid[i][1] &&  result[biasSolid[i]] >= RangeSolid[i][0]){
            final_result[biasSolid[i]] += result[biasSolid[i]]
        }
        else if (biasSolid[i] in result && result[biasSolid[i]] >= RangeSolid[i][1]) {
            // if (biasSolid[i] == "qt") {
            //     final_result[biasSolid[i]] += result[biasSolid[i]];
            // } else {
            let output = checkUnitSolid(result[biasSolid[i]], newRangeSolid[i][1], (count = 0)); // call recurssion function
            final_result[biasSolid[i]] = result[biasSolid[i]] - output; // subtract maximum range
            final_result[biasSolid[i - 1]] += conversionSolid[i][i - 1] * output; // convert subtracted value
            //     final_result[biasSolid[i - 1]] += conversionSolid[i][i - 1] * result[biasSolid[i]];
            // }
        } else if (biasSolid[i] in result && result[biasSolid[i]] <= RangeSolid[i][0]) {
            if(biasSolid[i]=="pinch") {
                final_result[biasSolid[i]] = result[biasSolid[i]];
            } else {
                final_result[biasSolid[i+1]] += conversionSolid[i][i + 1] * result[biasSolid[i]]
            }
        }
    }
    var count = 0;
    for (key in final_result) {
        if (final_result.hasOwnProperty(key) && final_result[key] == 0) {
            delete final_result[key];
            count += 1;
        }
    }
    for (key in final_result) {
        if (count < 3 && key == 'pinch') {
            delete final_result[key];
        }
    }
    console.log(final_result);
    return final_result
}

/* Conversion function for liquid type */
const conversionLiquid = [
    [1, 4, 64, 192, 3072],
    [1 / 4, 1, 16, 48, 768],
    [1 / 64, 1 / 16, 1, 3, 48],
    [1 / 192, 1 / 48, 1 / 3, 1, 16],
    [1 / 3072, 1 / 768, 1 / 48, 1 / 16, 1]
];
const biasLiquid = {
    0: "qt.",
    1: "c.",
    2: "T.",
    3: "t.",
    4: "pinch"
};
let RangeLiquid = {
    4: [0, 0],
    3: [0.125, 2.875],
    2: [1, 3],
    1: [0.25, 3.75],
    0: [1, 999]
};
let newRangeLiquid = {
    4: [0, 1],
    3: [0.125, 3],
    2: [1, 4],
    1: [0.25, 4],
    0: [1, 999]
};

function checkUnitLiquid(result, range, count) {
    const final = count;
    if (result - range >= 0) {
        count += range;
        return checkUnitLiquid(result - range, range, count);
    } else {
        return final;
    }
}
function convertLiquidNew(type, total, nextTotal = 0) {
    if (parseFloat(total).toFixed(2) === 0.0) {
        return 0;
    }
    //total= (Math.round((total + Number.EPSILON) * 100) / 100);
    let final_total=total;
    let answer=0;
    let cupFrac='';
    let solid_answer='';
    let final_result = {
        'qt.': 0,
        'c.': 0,
        'T.': 0,
        't.': 0,
    };

    final_total = total*convertLiquidLCD[type];
    total_pinches = final_total;
    next_total_pinches = 0;
    if(nextTotal != 0){
        next_total_pinches = nextTotal*convertLiquidLCD[type];
    }
    console.log("liquid type"+type);
    console.log("liquid total "+final_total);
    for ($i = 0; $i <= 12; $i++) {
        index = $i;
        if(final_total>=liquidLCD[index]){
            if($i != 0 && nextTotal != 0 && total_pinches != final_total){
                errorRata = final_total/total_pinches;
                if(errorRata <= 0.05){
                    lastNearValueDiff = liquidLCD[index -1] - final_total;
                    currentNearValueDiff = final_total - liquidLCD[index];
                    console.log('lastNearValueDiff ->' + lastNearValueDiff);
                    console.log('currentNearValueDiff ->'+currentNearValueDiff);
                    if(lastNearValueDiff < currentNearValueDiff){
                        if(liquidLCD[index - 1] > 15){
                            newErrorRate = liquidLCD[index - 1] / total_pinches;
                            if(newErrorRate <= 0.05){
                                if(liquidLCD[index - 1] == 1536 || liquidLCD[index - 1] == 384 || liquidLCD[index - 1] == 24 || liquidLCD[index - 1] == 8){
                                    $i = $i-1;
                                    index = $i;
                                    final_total = liquidLCD[index];
                                } else {
                                    final_total = 0;
                                }
                            } else {
                                $i = $i-1;
                                index = $i;
                                console.log('lastPinch :'+index + '=>'+ liquidLCD[index]);
                                final_total = liquidLCD[index];
                            }
                        } else {
                            final_total = 0;
                        }

                    } else {
                        if(liquidLCD[index] == 1536 || liquidLCD[index] == 384 || liquidLCD[index] == 24 || liquidLCD[index] == 8){
                            final_total = liquidLCD[index];
                        } else {
                            final_total = 0;
                        }
                    }

                }
            }
            if(final_total != 0){
                final_total=final_total-liquidLCD[index];
                final_result[liquidUnitLCD[index]] = (final_result[liquidUnitLCD[index]] + countPlusLiquid[index]);
                console.log(liquidUnitLCD[index],countPlusLiquid[index]);
            }
            if(Math.abs(final_total) >= liquidLCD[index] && final_total != 0){
                $i--;
            }
        }
        // console.log('ans: '+Math.abs(final_total));
        //if(Math.abs(final_total>=liquidLCD[$i]){
        // console.log('loop: '+countPlusLiquid[index]+' total:'+liquidLCD[index]);
        // while(Math.abs(final_total)>=liquidLCD[index] || Math.round(final_total)>=liquidLCD[index]){
        //     final_total=Math.abs(final_total)-liquidLCD[index];
        //     final_result[liquidUnitLCD[index]] = (final_result[liquidUnitLCD[index]] + countPlusLiquid[index]);
        //     //console.log('ans: '+final_result[biasSolidNew[$i]]);
        //     //final_result[$i] += final_result[$i] + 1;
        // }
        //}
    }
    if((final_result['c.'] != 0 || final_result['qt.'] != 0 ) && final_result['t.'] < 1){
        final_result['t.'] = 0;
    }
    console.log(final_result);
    return final_result;
    /*for ($i = 0; $i < conversionLiquid.length; $i++) {
        if (conversionLiquid[$i][type] <= total) {
            result[biasLiquid[$i]] = (result[biasLiquid[$i]] || 0) + 1;
            convertLiquid(type, total - conversionLiquid[$i][type], result);
        }
    }
    if (!jQuery.isEmptyObject(result)) {
        return filterResultLiquid(result);
    }*/
    // return filterResultLiquid(result);
}

//not needed
function convertLiquid(type, total, result = {}) {
    if (parseFloat(total).toFixed(2) === 0.0) {
        return 0;
    }

    for ($i = 0; $i < conversionLiquid.length; $i++) {
        if (conversionLiquid[$i][type] <= total) {
            result[biasLiquid[$i]] = (result[biasLiquid[$i]] || 0) + 1;
            convertLiquid(type, total - conversionLiquid[$i][type], result);
        }
    }
    if (!jQuery.isEmptyObject(result)) {
        return filterResultLiquid(result);
    }
    // return filterResultLiquid(result);
}

function filterResultLiquid(result) {
    let final_result = {
        'qt.':0,
        'c.': 0,
        'T.': 0,
        't.': 0,
        'pinch': 0
    };
    var i = 4;
    for (i; i >= 0; i--) {
        if (biasLiquid[i] in result && result[biasLiquid[i]] <= RangeLiquid[i][1] &&  result[biasLiquid[i]] >= RangeLiquid[i][0]){
            final_result[biasLiquid[i]] += result[biasLiquid[i]]
        }
        else if (biasLiquid[i] in result && result[biasLiquid[i]] >= RangeLiquid[i][1]) {
            if (biasLiquid[i] == "qt.") {
                final_result[biasLiquid[i]] += result[biasLiquid[i]];
            } else {
                let output = checkUnitLiquid(result[biasLiquid[i]], newRangeLiquid[i][1], (count = 0)); // call recurssion function
                final_result[biasLiquid[i]] = result[biasLiquid[i]] - output; // subtract maximum range
                final_result[biasLiquid[i - 1]] += conversionLiquid[i][i - 1] * output; // convert subtracted value into higher
                // final_result[biasLiquid[i - 1]] += conversionLiquid[i][i - 1] * result[biasLiquid[i]];
            }
        } else if (biasLiquid[i] in result && result[biasLiquid[i]] <= RangeLiquid[i][0]) {
            if(biasLiquid[i]=="pinch") {
                final_result[biasLiquid[i]] = result[biasLiquid[i]];
            } else {
                final_result[biasLiquid[i+1]] += conversionLiquid[i][i + 1] * result[biasLiquid[i]]
            }
        }
    }
    for (key in final_result) {
        if (final_result.hasOwnProperty(key) && final_result[key] == 0) {
            delete final_result[key];
        }
    }
    return final_result
}

//not needed
function convertImperialSolid(type, total, result = {}) {
    if (parseFloat(total).toFixed(2) === 0.0) {
        return 0;
    }

    for ($i = 0; $i < conversionSolid.length; $i++) {
        if (conversionSolid[$i][type] <= total) {
            result[biasSolid[$i]] = (result[biasSolid[$i]] || 0) + 1;
            convertSolid(type, total - conversionSolid[$i][type], result);
        }
    }
    if (!jQuery.isEmptyObject(result)) {
        return filterResultSolid(result);
    }
}

function convertToImperialSolid(type, total,each_gram, slname, unit_format, alter_name,that,serving,imp_met) {
    var unit= 'oz';
    var final= total+' '+unit+'. '+slname;
    var extra_ind_id =that.closest('.sl-name').find('.extra_ing_amount');
    var defaultExtraIngUnit = that.closest('.sl-name').find('.unit_format').attr('data-default');
    var CurrentExtraIngUnitVal = that.closest('.sl-name').find('.unit_format').val();
    var constant =that.find('span.measure-value').attr('data-constant');
    var amount_quantity = that.find('span.measure-value').attr('data-quantity');
    var each_weight = each_gram;
    // var total_weight_for_e3 = each_gram  * amount_quantity;
    //new
    var extra_ing = extra_ind_id.attr('data-default');
    console.log("extra_ing123",extra_ing);
    var total_temp = total;
    var each = (total_temp/each_gram).toPrecision(1);
    var each_for_l1l2 = each;
    var each_unit = 'oz';
    that.closest('.collapse').find('.unit_format').val(unit);
    if(extra_ing == 0){
        if (type == 1) {
            console.log("extra imp_met_type=1",total);
            if (parseFloat(total).toFixed(2) === 0.0) {
                return 0;
            }
            if (parseFloat(total).toFixed(2)  >=  450.71) {
                total = (total / 453.6 ).toFixed(2);
                unit = 'lbs';
                that.find('span.measure_unit').text('lbs');
            } else {
                total = (total / 28.35).toFixed(2);
                unit = 'oz';
                that.find('span.measure_unit').text('oz');
            }
            if (parseFloat(each_gram).toFixed(2)  >=  450.71) {
                each_gram = (each_gram / 453.6 ).toFixed(2);
                each_unit = 'lb';
            } else {
                each_gram = (each_gram / 28.35).toFixed(2);
                each_unit = 'oz';
            }
            // console.log("each_gram each",each_gram);
            if(unit_format=='E1'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname;
            }
            if(unit_format=='E2'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname+" ("+total+" "+unit+" total)";
                // final = slname + "(" +total + unit + ")";
            }
            if(unit_format=='E3'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname+" ("+total+" "+unit+" total, "+each_gram+" "+each_unit+" each)";
            }
            if(unit_format=='W1'){
                final = slname;
            }
            if(unit_format=='W2'){
                alter_name = (alter_name!='')?" "+alter_name:'';
                qty_each = total / each_weight;
                console.log(qty_each);
                final = slname +" " + Math.ceil(qty_each) + " " +alter_name;
                // final = total+" "+unit+" "+slname+" ("+each+alter_name+")";
            }
            if(unit_format=='W3'){
                alter_name = (alter_name!='')?" "+alter_name:'';
                qty_each = total / each_weight;
                
                final = slname + " ("+ Math.ceil(qty_each) + " " +alter_name+ ", " +each_gram+" "+each_unit+  " each)"  ;
                // final = slname+" ("+each+alter_name+", "+qty_each+" "+each_unit+" each)";
                // final = slname + " (" + each + " " +each_unit + ")" ;
            }
            if(unit_format=='L2'){
                qty_each = total / each_weight;
                alert("qty_each"+qty_each);
                final = slname + " (" +  Math.ceil(qty_each) + " each)";
            }
            if(unit_format=='L1'){
                var total_temp = each_for_l1l2;
                if (total_temp  >=  3691) {
                    total = (total_temp / 3785 ).toFixed(2);
                    unit = "Gallons";
                    new_total=total.toString().split(".");
                    if(new_total[1].indexOf(0) == 0){
                        total=toDecimal(total);
                    }
                    // final = total+" "+unit+" "+slname;
                    final = slname;
                }else if (parseFloat(total_temp).toFixed(2)  >=  900 && parseFloat(total_temp).toFixed(2)  <= 3690) {
                    total = (total_temp / 946.4 ).toFixed(2);
                    unit = "qt";
                    new_total=total.toString().split(".");
                    if(new_total[1].indexOf(0) == 0){
                        total=toDecimal(total);
                    }
                    // final = total+" "+unit+" "+slname;
                    final = slname;
                }else if (parseFloat(total_temp).toFixed(2)  >=  471 && parseFloat(total_temp).toFixed(2)  <= 899) {
                    total = (total_temp / 473.2 ).toFixed(2);
                    unit = "pints";
                    new_total=total.toString().split(".");
                    // console.log("new_total",new_total);
                    // console.log("zero zero substring",new_total[1].indexOf(0));
                    if(new_total[1].indexOf(0) == 0){
                        total=toDecimal(total);
                    }
                    // final = total+" "+unit+" "+slname;
                    final = slname;
                } else {
                    total = (total_temp / 29.57).toFixed(2);
                    unit = "fl oz";
                    new_total=total.toString().split(".");
                    // console.log("new_total",new_total);
                    // console.log("zero zero substring",new_total[1].indexOf(0));
                    if(new_total[1].indexOf(0) == 0){
                        total=toDecimal(total);
                    }
                    // final = total+" "+unit+" "+slname;
                    final = slname;
                }
            }
            console.log("new_total",total);
            //set total to extra ing.
            if(unit_format != 'E2' && unit_format != 'E3' && unit_format != 'E1' && unit_format != 'L2'){
                extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met);
                that.closest('.collapse').find('.unit_format').val(unit);
                that.closest('.sl-name').find('span.unit_format').text(unit);
            }
            console.log("new_total1",total);
            //set value to unit format

            that.find('span.measure-value').text(final);
        }
        if (type == 2) {
            console.log("extra imp_met_type=2",total);
            // console.log("type==2",type);
            // alert("total total"+total);
            // if (unit_format != 'L1'){
            if (parseFloat(total).toFixed(2) === 0.0) {
                console.log("parse0.0");
                return 0;
            }
            if (parseFloat(total).toFixed(2)  >=  3691) {
                // console.log("total total",total);
                // console.log("Gallons",total);
                console.log("parse3691");
                total = (total / 3785 ).toFixed(2);
                unit = 'Gallons';
                // console.log("Gallons",unit);
                //that.find('span.measure_unit').text('Gallons');
                //that.find('span.measure-value').text(total+' Gallons. '+slname);
            }else if (parseFloat(total).toFixed(2)  >=  900 && parseFloat(total).toFixed(2)  <= 3690) {
                // console.log("qt",total);
                // alert("900 ti 3690");
                console.log("parse900to3690",total);
                total = (total / 946.4 ).toFixed(2);
                unit = 'qt';
                // alert("qt total"+total);
                // console.log('qt',unit);
                //that.find('span.measure_unit').text('qt');
                //that.find('span.measure-value').text(total+' qt. '+slname);
            }else if (parseFloat(total).toFixed(2)  >=  471 && parseFloat(total).toFixed(2)  <= 899) {
                // if(unit_format != "L1"){
                // console.log("pints 123",total);
                console.log("parse471to899");
                total = (total / 473.2 ).toFixed(2);
                unit = 'pints';
                console.log("pints",unit);
            } else {

                console.log("parsefloz");
                total = (total / 29.57).toFixed(2);
                unit = 'fl oz';
            }



            if(unit_format=='E1'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname;
            }
            if(unit_format=='E2'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname+" ("+total+" "+unit+" total)";
            }
            if(unit_format=='E3'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname+" ("+total+" "+unit+" total, "+each_gram+" "+each_unit+" each)";
            }
            if(unit_format=='W1'){
                final = slname;
            }
            if(unit_format=='W2'){
                alter_name = (alter_name!='')?" "+alter_name:'';
                qty_each = total / each_weight;
                final = slname +" " + Math.ceil(qty_each) + " " +alter_name;
            }
            if(unit_format=='W3'){
                alter_name = (alter_name!='')?" "+alter_name:'';
                qty_each = total / each_weight;
                final = slname + " ("+ Math.ceil(qty_each) + " " +alter_name+ ", " +each_gram+" "+each_unit+  " each)"  ;
            }
            if(unit_format=='L2'){
                qty_each = total / each_weight;
                alert("qty_each"+qty_each);
                // that.closest('.collapse').find('.unit_format').val('each');
                // that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname + " (" +  Math.ceil(qty_each) + " each)";
            }
            if(unit_format=='L1'){
                var tempo=calc_for_liquid();
                if(tempo == NaN && tempo == undefined){
                    let id=that.find('span.measure-value').attr('data-id');
                }else{
                    total=tempo;
                    unit='fl oz';
                    console.log("calc_obj_temp in L1",calc_obj_temp);
                }
                final = slname;
                // console.log("unit 123",unit);
                // },100);




            }
            // var temp=decimalToFraction(total);
            // console.log("temp temp temp123",unit);
            if(unit_format != 'E2' && unit_format != 'E3' && unit_format != 'E1' ){
                extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met);
                that.closest('.collapse').find('.unit_format').val(unit);
                that.closest('.sl-name').find('span.unit_format').text(unit);
            }

            that.find('span.measure-value').text(final);
        }

   }else{
        if(imp_met != 0  && imp_met){
            // alert("HEllo IF");
            console.log("totalIF",total);
            extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met);
        }else{
            extra_ing_adding(extra_ing,total,extra_ind_id,serving,type,imp_met);
        }
    }
    console.log("qwerty",total);
}
function convertToImperialSolid_new(type, total,each_gram, slname, unit_format, alter_name,that,serving,imp_met) {
    alert("convertToImperialSolid_new");
    var unit= 'oz';
    var final= total+' '+unit+'. '+slname;
    var extra_ind_id =that.closest('.sl-name').find('.extra_ing_amount');
    var defaultExtraIngUnit = that.closest('.sl-name').find('.unit_format').attr('data-default');
    var CurrentExtraIngUnitVal = that.closest('.sl-name').find('.unit_format').val();
    var constant =that.find('span.measure-value').attr('data-constant');
    var extra_ing = extra_ind_id.attr('data-default');
    var total_temp = total;
    var amount_quantity = that.find('span.measure-value').attr('data-quantity');
    var each_weight = each_gram;
    var each = (total_temp/each_gram).toPrecision(1);
    var each_unit = 'oz';

    that.closest('.collapse').find('.unit_format').val(unit);

    if(extra_ing == 0){
        if (type == 1) {
            if (parseFloat(total).toFixed(2) === 0.0) {
                return 0;
            }
            if (parseFloat(total).toFixed(2)  >=  450.71) {
                total = (total / 453.6 ).toFixed(2);
                unit = 'lb';
                that.find('span.measure_unit').text('lb');
            } else {
                total = (total / 28.35).toFixed(2);
                unit = 'oz';
                that.find('span.measure_unit').text('oz');
            }
            if(unit_format=='E1'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname;
            }
            if(unit_format=='E2'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname+" ("+total+" "+unit+" total)";
            }
            if(unit_format=='E3'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname+" ("+total+" "+unit+" total, "+each_gram+" "+each_unit+" each)";
            }
            if(unit_format=='W1'){

                final = slname;
            }
            if(unit_format=='W2'){
                alter_name = (alter_name!='')?" "+alter_name:'';
                qty_each = total / each_weight;
                final = slname +" " + Math.ceil(qty_each) + " " +alter_name;
            }
            if(unit_format=='W3'){
                alter_name = (alter_name!='')?" "+alter_name:'';
                qty_each = total / each_weight;
                final = slname + " ("+ Math.ceil(qty_each) + " " +alter_name+ ", " +each_gram+" "+each_unit+  " each)";
            }
            if(unit_format=='L2'){
                qty_each = total / each_weight;
                final = slname + " (" +  Math.ceil(qty_each) + " each)";
            }
            if(unit_format=='L1'){
                console.log("unit_format unti_format");
                if (total_temp  >=  3691) {
                    total = (total_temp / 3785 ).toFixed(2);
                    unit = "Gallons";
                    new_total=total.toString().split(".");
                    if(new_total[1].indexOf(0) == 0){
                        total=toDecimal(total);
                    }
                    final = slname;
                }else if (parseFloat(total_temp).toFixed(2)  >=  900 && parseFloat(total_temp).toFixed(2)  <= 3690) {
                    total = (total_temp / 946.4 ).toFixed(2);
                    unit = "qt";
                    new_total=total.toString().split(".");
                    if(new_total[1].indexOf(0) == 0){
                        total=toDecimal(total);
                    }
                    // final = total+" "+unit+" "+slname;
                    final = slname;
                }else if (parseFloat(total_temp).toFixed(2)  >=  471 && parseFloat(total_temp).toFixed(2)  <= 899) {
                    total = (total_temp / 473.2 ).toFixed(2);
                    unit = "pints";
                    new_total=total.toString().split(".");
                    // console.log("new_total",new_total);
                    // console.log("zero zero substring",new_total[1].indexOf(0));
                    if(new_total[1].indexOf(0) == 0){
                        total=toDecimal(total);
                    }
                    // final = total+" "+unit+" "+slname;
                    final = slname;
                } else {
                    total = (total_temp / 29.57).toFixed(2);
                    unit = "fl oz";
                    new_total=total.toString().split(".");
                    // console.log("new_total",new_total);
                    // console.log("zero zero substring",new_total[1].indexOf(0));
                    if(new_total[1].indexOf(0) == 0){
                        total=toDecimal(total);
                    }
                    // final = total+" "+unit+" "+slname;
                    final = slname;
                }
            }
            //set total to extra ing.
            // extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met);
            // total=parseFloat(total).toFixed(2);
            if(unit_format != 'E2' && unit_format != 'E3' && unit_format != 'E1' && unit_format != 'L2') {
                extra_ind_id.val(total);
                extra_ind_id.attr('data-default', 0);
                that.attr('data-default_initial_value', total);
                extra_ind_id.attr('data-refresh_default_value', total);
                extra_ind_id.attr('data-refresh_updated_value', total);
                that.closest('.collapse').find('.unit_format').val(unit);
                that.closest('.sl-name').find('span.unit_format').text(unit);

            }
            //set value to unit format
            that.find('span.measure-value').text(final);
        }
    }else{
        if(imp_met != 0  && imp_met){
            // alert("HEllo IF");
            console.log("totalIF",total);
            extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met);
        }else{
            extra_ing_adding(extra_ing,total,extra_ind_id,serving,type,imp_met);

        }
    }
}
function convertSLToGram(type, total,each_gram, slname, unit_format, alter_name,that,serving,imp_met) {
    var unit= 'g';
    /*//Add extra ing amount
    var extra_ing = that.closest('.collapse').find('.extra_ing_amount').val();
    total = (extra_ing)?(parseFloat(extra_ing)+parseFloat(total)) : total; */
    var extra_ind_id =that.closest('.sl-name').find('.extra_ing_amount');
    var defaultExtraIngUnit = that.closest('.sl-name').find('.unit_format').attr('data-default');
    var CurrentExtraIngUnitVal = that.closest('.sl-name').find('.unit_format option:first').val();
    var amount_quantity = that.find('span.measure-value').attr('data-quantity');
    var each_weight = each_gram;
    //old
    // var extra_ing = extra_ind_id.val();

    //new
    var extra_ing = extra_ind_id.attr('data-default');
    var liquid = 0;
    if(defaultExtraIngUnit== unit && extra_ing==extra_ind_id.attr('data-default')){
        console.log('if:extra_ing',extra_ing);
    }else {
        // extra_ing = (extra_ing)?((extra_ing * 28.35).toFixed(2)):0;
        if(CurrentExtraIngUnitVal=='oz'){
            extra_ing = (extra_ing)?((extra_ing * 28.35).toFixed(2)):0;
        }

    }
    // }
    //console.log(total +'ex '+extra_ing);
    var total_temp = total;
    var each = (total_temp/each_gram).toPrecision(1);
    var final= total+' '+unit+'. '+slname;
    var each_unit = 'kg';
    that.closest('.collapse').find('.unit_format').val(unit);
    if (extra_ing == 0){
       // alert("type metrci"+imp_met);
       if (type == 1) {
           unit= 'g';
           if (total >= 1000) {
               total = (total / 1000).toFixed(2);
               unit = 'kg';
               that.find('span.measure_unit').text('kg');
               //that.find('span.measure-value').text(total+' kg. '+slname);

           } else {
               total = parseFloat(total.toString()).toFixed(2);
               // extra_ind_id.val(total);
               // console.log("hello total",total);
               that.find('span.measure_unit').text('grams');
               //that.find('span.measure-value').text(total+' g. '+slname);
           }
           //for each gram
           if (each_gram  >=  1000) {
               each_gram = (each_gram / 1000).toFixed(2);
               each_unit = 'kg';
           } else {
               each_gram = toDecimal(each_gram.toString())
               each_unit = 'g';
           }
           if(unit_format=='E1'){
               extra_ind_id.val(amount_quantity);
               that.closest('.collapse').find('.unit_format').val('each');
               that.closest('.sl-name').find('span.unit_format').text('each');
               final = slname;
           }
           if(unit_format=='E2'){
               extra_ind_id.val(amount_quantity);
               that.closest('.collapse').find('.unit_format').val('each');
               that.closest('.sl-name').find('span.unit_format').text('each');
               final = slname+" ("+total+" "+unit+" total)";
           }
           if(unit_format=='E3'){
               extra_ind_id.val(amount_quantity);
               that.closest('.collapse').find('.unit_format').val('each');
               that.closest('.sl-name').find('span.unit_format').text('each');
               final = slname+" ("+total+" "+unit+" total, "+each_gram+" "+each_unit+" each)";
           }
           if(unit_format=='W1'){
               final = slname;
           }
           if(unit_format=='W2'){
               alter_name = (alter_name!='')?" "+alter_name:'';
               qty_each = total / each_weight;
               final = slname +" " + Math.ceil(qty_each) + " " +alter_name;
           }
           if(unit_format=='W3'){
               alter_name = (alter_name!='')?" "+alter_name:'';
               qty_each = total / each_weight;
               final = slname + " ("+ Math.ceil(qty_each) + " " +alter_name+ ", " +each_gram+" "+each_unit+  " each)"  ;
           }
           if(unit_format=='L2'){
               qty_each = total / each_weight;
               final = slname + " (" +  Math.ceil(qty_each) + " each)";
           }
           if(unit_format=='L1'){
               var each = ((total_temp-extra_ing)/each_gram).toFixed(2);
               //console.log('le'+each+'ext'+extra_ing);
               each = (extra_ing)?(parseFloat(extra_ing)+parseFloat(each)):0;

               if (each >= 1000) {
                   each = (each / 1000).toFixed(2);
                   // final = each+" l "+slname;
                   final = slname;
               }else{
                   each = toDecimal(each.toString());
                   // final = each+" ml "+slname;
                   final = slname;
               }
               that.closest('.sl-name').find('.unit_format').val('ml');
           }
           if(unit_format != 'E2' && unit_format != 'E3' && unit_format != 'E1' && unit_format != 'L2') {
               extra_ing_adding(extra_ing, total, extra_ind_id, serving, imp_met);
               //set new unit format
               that.closest('.sl-name').find('span.unit_format').text(unit);
           }
           that.find('span.measure-value').text(final);

       }
       if (type == 2) {
           console.log("type 2 gram");
           unit= 'ml';
           that.closest('.sl-name').find('.unit_format').val(unit);

           if (total >= 1000) {
               unit = 'l';
               total = (total / 1000).toFixed(2);
               // that.find('span.measure_unit').text('l');
               //that.find('span.measure-value').text(total+' l '+slname);
           } else {
               unit = 'ml';
               total = toDecimal(total.toString());
               // that.find('span.measure_unit').text('ml');
               //that.find('span.measure-value').text(total+' ml '+slname);
           }
           if (each_gram  >=  1000) {
               each_gram = (each_gram / 1000).toFixed(2);
               each_unit = 'l';
           } else {
               each_gram = toDecimal(each_gram.toString())
               each_unit = 'ml';
           }
           if(unit_format=='E1'){
               extra_ind_id.val(amount_quantity);
               that.closest('.collapse').find('.unit_format').val('each');
               that.closest('.sl-name').find('span.unit_format').text('each');
               final = slname;
           }
           if(unit_format=='E2'){
               extra_ind_id.val(amount_quantity);
               that.closest('.collapse').find('.unit_format').val('each');
               that.closest('.sl-name').find('span.unit_format').text('each');
               final = slname+" ("+total+" "+unit+" total)";
           }
           if(unit_format=='E3'){
               extra_ind_id.val(amount_quantity);
               that.closest('.collapse').find('.unit_format').val('each');
               that.closest('.sl-name').find('span.unit_format').text('each');
               final = slname+" ("+total+" "+unit+" total, "+each_gram+" "+each_unit+" each)";
           }
           if(unit_format=='W1'){
               final = slname;
           }
           if(unit_format=='W2'){
               alter_name = (alter_name!='')?" "+alter_name:'';
               qty_each = total / each_weight;
               final = slname +" " + Math.ceil(qty_each) + " " +alter_name;
           }
           if(unit_format=='W3'){
               alter_name = (alter_name!='')?" "+alter_name:'';
               qty_each = total / each_weight;
               final = slname + " ("+ Math.ceil(qty_each) + " " +alter_name+ ", " +each_gram+" "+each_unit+  " each)"  ;
           }
           if(unit_format=='L2'){
               qty_each = total / each_weight;
               final = slname + " (" +  Math.ceil(qty_each) + " each)";
           }
           if(unit_format=='L1'){
               var tempo=calc_for_liquid();
               console.log("gram tempo",tempo);
               var each = (total_temp/each_gram).toFixed(2);

               //console.log('unit_format '+unit);
               if (each >= 1000) {
                   each = (each / 1000).toFixed(2);
                   // final = each+" l "+slname;
                   final = slname;
               }else{
                   each = toDecimal(each.toString());
                   console.log("each each each",each);
                   // final = each+" ml "+slname;
                   final = slname;
               }
           }
           if(unit_format != 'E2' && unit_format != 'E3' && unit_format != 'E1' && unit_format != 'L2') {
               extra_ing_adding(extra_ing, total, extra_ind_id, serving, imp_met);
               //set new unit format
               that.closest('.sl-name').find('span.unit_format').text(unit);
           }
           that.find('span.measure-value').text(final);
       }
   }else{
        if(imp_met != 1 ){
            extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met);
        }else{
            extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met);
        }

   }
    console.log("total new gram total",total);
}
function convertSLToGram_new(type, total,each_gram, slname, unit_format, alter_name,that,serving,imp_met) {
    var unit= 'g';
    var extra_ind_id =that.closest('.sl-name').find('.extra_ing_amount');
    var defaultExtraIngUnit = that.closest('.sl-name').find('.unit_format').attr('data-default');
    var CurrentExtraIngUnitVal = that.closest('.sl-name').find('.unit_format option:first').val();
    var extra_ing = extra_ind_id.attr('data-default');
    var liquid = 0;
    var amount_quantity = that.find('span.measure-value').attr('data-quantity');
    var each_weight = each_gram;
    if(defaultExtraIngUnit== unit && extra_ing==extra_ind_id.attr('data-default')){
        console.log('if:extra_ing',extra_ing);
        // total = (extra_ing)?(parseFloat(extra_ing)+parseFloat(total)) : total;
        // console.log("totaldefault",total);
    }else {
        // extra_ing = (extra_ing)?((extra_ing * 28.35).toFixed(2)):0;
        if(CurrentExtraIngUnitVal=='oz'){
            extra_ing = (extra_ing)?((extra_ing * 28.35).toFixed(2)):0;
        }
    }
    // }
    //console.log(total +'ex '+extra_ing);
    var total_temp = total;
    var each = (total_temp/each_gram).toPrecision(1);
    var final= total+' '+unit+'. '+slname;
    var each_unit = 'kg';
    that.closest('.collapse').find('.unit_format').val(unit);
    if (extra_ing == 0){
        if (type == 1) {
            unit= 'g';
            if (total >= 1000) {
                total = (total / 1000).toFixed(2);
                unit = 'kg';
                that.find('span.measure_unit').text('kg');
            } else {
                total = parseFloat(total.toString()).toFixed(2);
                that.find('span.measure_unit').text('grams');

            }
            //for each gram
            if (each_gram  >=  1000) {
                each_gram = (each_gram / 1000).toFixed(2);
                each_unit = 'kg';
            } else {
                each_gram = toDecimal(each_gram.toString())
                each_unit = 'g';
            }
            if(unit_format=='E1'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname;
            }
            if(unit_format=='E2'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname+" ("+total+" "+unit+" total)";
            }
            if(unit_format=='E3'){
                extra_ind_id.val(amount_quantity);
                that.closest('.collapse').find('.unit_format').val('each');
                that.closest('.sl-name').find('span.unit_format').text('each');
                final = slname+" ("+total+" "+unit+" total, "+each_gram+" "+each_unit+" each)";
            }
            if(unit_format=='W1'){
                final = slname;
            }
            if(unit_format=='W2'){
                alter_name = (alter_name!='')?" "+alter_name:'';
                qty_each = total / each_weight;
                final = slname +" " + Math.ceil(qty_each) + " " +alter_name;
            }
            if(unit_format=='W3'){
                alter_name = (alter_name!='')?" "+alter_name:'';
                qty_each = total / each_weight;
                final = slname + " ("+ Math.ceil(qty_each) + " " +alter_name+ ", " +each_gram+" "+each_unit+  " each)"  ;
            }
            if(unit_format=='L2'){
                qty_each = total / each_weight;
                final = slname + " (" +  Math.ceil(qty_each) + " each)";
            }
            if(unit_format=='L1'){
                var each = ((total_temp-extra_ing)/each_gram).toFixed(2);
                //console.log('le'+each+'ext'+extra_ing);
                each = (extra_ing)?(parseFloat(extra_ing)+parseFloat(each)):0;

                if (each >= 1000) {
                    each = (each / 1000).toFixed(2);
                    final = slname;
                }else{
                    each = toDecimal(each.toString());
                    final = slname;
                }
                that.closest('.sl-name').find('.unit_format').val('ml');
            }
            // extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met);
            if(unit_format != 'E2' && unit_format != 'E3' && unit_format != 'E1' && unit_format != 'L2') {
                extra_ind_id.val(total);
                extra_ind_id.attr('data-default', 0);
                that.attr('data-default_initial_value', total);
                extra_ind_id.attr('data-refresh_default_value', total);
                extra_ind_id.attr('data-refresh_updated_value', total);
                //set new unit format
                that.closest('.sl-name').find('span.unit_format').text(unit);
            }
            that.find('span.measure-value').text(final);
        }
    }else{
        if(imp_met != 1 ){
            extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met);
        }else{
            extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met);
        }
    }
}
function unitConversion(extra_ing_value,extra_ing_unit,that){
        let extra_ing='';
        let that_is= $(that).parents('.unit-select').find('span.unit_format');
        let unit='';
        switch (extra_ing_unit){
            case 'fl oz':
                if(extra_ing_value && extra_ing_value >= 12.8){
                    console.log("I am In If",extra_ing_value);
                    // alert("If floz");
                    let fl_oz= extra_ing_value/12.8;
                    extra_ing=fl_oz.toFixed(2);
                    unit='pints';
                    setTimeout(function () {
                        $(that).val(extra_ing);
                        $(that).attr('value',extra_ing);
                        $(that).attr('data-default',extra_ing);
                        // that_is.text(unit);
                    }, 500);
                    // console.log('fl_oz unit',unit);
                    return {extra_ing,unit}
                }else{
                    // alert("Else floz");
                    console.log("I am In else",extra_ing_value);
                    console.log("extra_ing_unit",extra_ing_unit);
                    extra_ing=extra_ing_value;
                    // unit=that_is.text();
                    unit=extra_ing_unit;
                    // console.log("unit");
                    // console.log("is is",that_is);
                    that_is.text(unit);
                    // console.log('fl_oz unit',unit);
                    return {extra_ing,unit}
                }
                break;
            case 'pints':
                if(extra_ing_value>=2){
                    // alert("IF pints");
                    console.log("I am In pint if",extra_ing_value);
                    let pints= extra_ing_value/2;
                    extra_ing=pints.toFixed(2);
                    unit='qt';
                    setTimeout(function () {
                        $(that).val(extra_ing);
                        $(that).attr('value',extra_ing);
                        $(that).attr('data-default',extra_ing);
                        that_is.text(unit);
                    }, 500);
                    return {extra_ing,unit}
                }else{
                    // alert("ELSE pints");
                    console.log("I am In pint else",extra_ing_value);
                    extra_ing=extra_ing_value;
                    unit=extra_ing_unit;
                    that_is.text(unit);
                    return {extra_ing,unit}
                }
                break;
            case 'oz':
                if (extra_ing_value >= 16){
                    var y = parseFloat(extra_ing_value) / 16;
                    unit='lb';
                    setTimeout(function () {
                        $(that).val(y.toFixed(2));
                        $(that).attr('value',y.toFixed(2));
                        $(that).attr('data-default',y);
                        that_is.text(unit);
                    }, 500);
                    extra_ing=y.toFixed(2);
                    console.log("log log log",extra_ing);
                    return {extra_ing, unit};
                }else{
                    extra_ing=extra_ing_value;
                    unit=extra_ing_unit;
                    return {extra_ing,unit}
                }
                break;
            case 'lb':
                if(extra_ing_value){
                    extra_ing=extra_ing_value;
                    unit=extra_ing_unit;
                    return {extra_ing,unit}
                }
                break;
            case 'pinch':
                if(extra_ing_value>=0.0625){
                    let pinch= extra_ing_value*0.0625;
                    extra_ing=pinch.toFixed(2);
                    unit='t';
                    setTimeout(function () {
                        $(that).val(extra_ing);
                        $(that).attr('value',extra_ing);
                        $(that).attr('data-default',extra_ing);
                        that_is.text(unit);
                    }, 500);
                    return {extra_ing,unit}
                }else{
                    extra_ing=extra_ing_value;
                    unit=extra_ing_unit;
                    return {extra_ing,unit}
                }
                break;
            case 't':
                if(extra_ing_value>=3){
                    let teaspoon= extra_ing_value*3;
                    extra_ing=teaspoon.toFixed(2);
                    unit='T';
                    setTimeout(function () {
                        $(that).val(extra_ing);
                        $(that).attr('value',extra_ing);
                        $(that).attr('data-default',extra_ing);
                        that_is.text(unit);
                    }, 500);
                    return {extra_ing,unit}
                }else{
                    extra_ing=extra_ing_value;
                    unit=extra_ing_unit;
                    return {extra_ing,unit}
                }
                break;
            case 'T':
                if(extra_ing_value>=16){
                    let Tablespoon=extra_ing_value/16;
                    extra_ing=Tablespoon.toFixed(2);
                    unit='c';
                    setTimeout(function () {
                        $(that).val(extra_ing);
                        $(that).attr('value',extra_ing);
                        $(that).attr('data-default',extra_ing);
                        that_is.text(unit);
                    }, 500);
                    return {extra_ing,unit}
                }else{
                    extra_ing=extra_ing_value;
                    unit=extra_ing_unit;
                    return {extra_ing,unit}
                }
                break;
            case 'c':
                if(extra_ing_value>=4){
                    let cup=extra_ing_value/4;
                    extra_ing=cup.toFixed(2);
                    unit='qt.';
                    setTimeout(function () {
                        $(that).val(extra_ing);
                        $(that).attr('value',extra_ing);
                        $(that).attr('data-default',extra_ing);
                        that_is.text(unit);
                    }, 500);
                    return {extra_ing,unit}
                }else{
                    extra_ing=extra_ing_value;
                    unit=extra_ing_unit;
                    return {extra_ing,unit}
                }
                break;
            case 'qt':
                if(extra_ing_value && extra_ing_value >= 4){
                    let quartz=extra_ing_value/4;
                    extra_ing=quartz.toFixed(2);
                    unit='gallons';
                    setTimeout(function () {
                        $(that).val(extra_ing);
                        $(that).attr('value',extra_ing);
                        $(that).attr('data-default',extra_ing);
                        that_is.text(unit);
                    }, 500);
                    return {extra_ing,unit}
                }else{
                    extra_ing=extra_ing_value;
                    unit=extra_ing_unit;
                    return {extra_ing,unit}
                }
                break;
            case 'g': case 'kg': case 'ml': case 'l':
                if(extra_ing_value){
                    extra_ing=extra_ing_value;
                    unit=that_is.text();
                    setTimeout(function () {
                        $(that).val(extra_ing);
                        $(that).attr('value',extra_ing);
                        $(that).attr('data-default',extra_ing);
                        that_is.text(unit);
                    }, 500);
                    return {extra_ing,unit}
                }
                break;
            default:
                extra_ing=extra_ing_value;
                unit=extra_ing_unit;
                return {extra_ing,unit}
        }
}
function extra_ing_adding(extra_ing,total,extra_ind_id,serving,imp_met){
        let temp_value= $(serving).closest('.sl-name').find('.recipeIngredientsServings').find('span.measure-value');
        // && imp_met statement is not required problematic imp_met
        if(extra_ind_id.attr('value')!== null && extra_ind_id.attr('value') != 0 /*&& imp_met*/){
                extra_ing_original_amount=serving.attr('data-new_original');
                let new_serving=Number(serving.attr('data-new_serving'));
                console.log("new_serving",new_serving);
                let old_serving=toDecimal(serving.attr('data-old_serving'));
                console.log("old_serving",new_serving);
                let temp_total= extra_ing_original_amount * new_serving;
                console.log('temp_total',temp_total);
                let new_total= temp_total / old_serving;
                console.log('new_total',new_total);
                extra_ind_id.val(new_total.toFixed(2));
                extra_ind_id.attr('value',new_total.toFixed(2));
                extra_ind_id.attr('data-default',new_total.toFixed(2));
                temp_value.attr('data-default_initial_value',new_total.toFixed(2));
                extra_ind_id.attr('data-refresh_default_value',extra_ind_id.val());
                extra_ind_id.attr('data-refresh_updated_value',new_total.toFixed(2));
                if(temp_value.attr('data-type')=='2'){
                    extra_ind_id.attr('value',total);
                    extra_ind_id.attr('data-default',total);
                    extra_ind_id.val(total);
                }
        }else{
            total=parseFloat(total).toFixed(2);
            extra_ind_id.val(total);
            extra_ind_id.attr('data-default',0);
            temp_value.attr('data-default_initial_value',total);
            extra_ind_id.attr('data-refresh_default_value',total);
            extra_ind_id.attr('data-refresh_updated_value',total);
        }

}
function calc_for_metric_liquid(){
    console.log('calc_for_metric_liquid',calc_obj_temp);
    if(typeof  calc_obj_temp === 'object'){
        if(calc_obj_temp != undefined){
            console.log("hello temp",calc_obj_temp);
            if (calc_obj_temp['qt.'] == undefined) {
                console.log("calc_obj_temp",calc_obj_temp);
                let cup_ans = calc_obj_temp['c.'] * liquidUnitFormat['c.'];
                let table_ans = calc_obj_temp['T.'] * liquidUnitFormat['T.'];
                let tea_ans = calc_obj_temp['t.'] * liquidUnitFormat['t.'];
                let pinch_ans = calc_obj_temp['pinch'] * liquidUnitFormat['pinch'];
                let final_ans = cup_ans + table_ans + tea_ans + pinch_ans;
                final_ans= final_ans * 29.574;
                console.log("final_ans metric",final_ans);
                return final_ans.toFixed(2);
            } else {
                console.log("hello calc_obj_temp else");
                console.log("calc_obj_temp",calc_obj_temp);
                let qt_ans = calc_obj_temp['qt.'] * liquidUnitFormat['qt.'];
                let cup_ans = calc_obj_temp['c.'] * liquidUnitFormat['c.'];
                let table_ans = calc_obj_temp['T.'] * liquidUnitFormat['T.'];
                let tea_ans = calc_obj_temp['t.'] * liquidUnitFormat['t.'];
                console.log("tea_ans tea_ans", tea_ans);
                console.log("table_ans table_ans", table_ans);
                console.log("cup_ans cup_ans", cup_ans);
                console.log("qt_ans qt_ans", qt_ans);
                let final_ans = cup_ans + table_ans + tea_ans + qt_ans;
                final_ans= final_ans * 29.574;
                return final_ans.toFixed(2);
            }
            // });
            return null;
        }
    }else{
        return final_ans=calc_obj_temp;
    }
}

function calc_for_liquid(){
    if(typeof  calc_obj_temp === 'object'){
        if(calc_obj_temp != undefined){
            console.log("hello temp",calc_obj_temp);
            if (calc_obj_temp['qt.'] == undefined) {
                console.log("calc_obj_temp",calc_obj_temp);
                let cup_ans = calc_obj_temp['c.'] * liquidUnitFormat['c.'];
                let table_ans = calc_obj_temp['T.'] * liquidUnitFormat['T.'];
                let tea_ans = calc_obj_temp['t.'] * liquidUnitFormat['t.'];
                let pinch_ans = calc_obj_temp['pinch'] * liquidUnitFormat['pinch'];
                let final_ans = cup_ans + table_ans + tea_ans + pinch_ans;
                return final_ans.toFixed(2);
            } else {
                console.log("hello calc_obj_temp else");
                console.log("calc_obj_temp",calc_obj_temp);
                let qt_ans = calc_obj_temp['qt.'] * liquidUnitFormat['qt.'];
                let cup_ans = calc_obj_temp['c.'] * liquidUnitFormat['c.'];
                let table_ans = calc_obj_temp['T.'] * liquidUnitFormat['T.'];
                let tea_ans = calc_obj_temp['t.'] * liquidUnitFormat['t.'];
                let final_ans = cup_ans + table_ans + tea_ans + qt_ans;
                return final_ans.toFixed(2);
            }
            // });
            return null;
        }
    }else{
        return final_ans=calc_obj_temp;
    }
};
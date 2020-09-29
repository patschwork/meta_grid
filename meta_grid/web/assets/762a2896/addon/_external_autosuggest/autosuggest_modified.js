(function(mod) {
    if (typeof exports === 'object' && typeof module === 'object') {
        // CommonJS
        mod(// require('codemirror/lib/codemirror'),
        require('../../lib/codemirror'), // require('codemirror/mode/markdown/markdown'),
        require('../../mode/markdown/markdown'), // require('codemirror/addon/hint/show-hint')
        require('../hint/show-hint'));
    } else if (typeof define === 'function' && define.amd) {
        // AMD
        define([// 'codemirror/lib/codemirror',
        // 'codemirror/mode/markdown/markdown',
        // 'codemirror/addon/hint/show-hint'
        '../../lib/codemirror', '../../mode/markdown/markdown', '../hint/show-hint'], mod);
    } else {
        // Plain browser env
        mod(CodeMirror);
    }
})(function(CodeMirror) {
    'use strict';

    CodeMirror.defineOption('autoSuggest', [], function(cm, value, old) {
        var fromParent;
        var toParent;
        cm.on('inputRead', function(cm, change) {
            var mode = cm.getModeAt(cm.getCursor());
            for (var i = 0, len = value.length; i < len; i++) {
                var checkmode = mode.name === value[i].mode ? true : false;
                var doc = cm.getDoc();
                var cursor = doc.getCursor();
                // gets the line number in the cursor position
                var line = doc.getLine(cursor.line);
                // get the line contents
                var currentStartChar = value[i].startChar;
                var lengthOfstartChar = currentStartChar.length;
                var checkStartChar = line.substring(line.length - lengthOfstartChar, line.length) === currentStartChar ? true : false;
                var thisCouldBeStartChar = line.substring(0,currentStartChar.length) === currentStartChar ? true : false;
                if (thisCouldBeStartChar) var filterCriteria = line.substring(currentStartChar.length,line.length);
                if (checkmode && (checkStartChar || thisCouldBeStartChar)) {
                    cm.showHint({
                        completeSingle: false,
						async: false,
                        hint: function(cm, options) {
                            var cur = cm.getCursor()
                              , token = cm.getTokenAt(cur);

							var start = token.start - 1
                              , end = token.end;

                            var completion = cm.state.completionActive;
                            var pick = completion.pick;

                            // diese Funktion wird nach dem Selektieren eines Wertes aufgerufen
                            completion.pick = function (data, i) {
								var completion = data.list[i];

                                var newBegin = CodeMirror.Pos(cur.line, start - 1);

                                var doc1 = cm.getDoc();
                                var cursor1 = doc1.getCursor();
                                // gets the line number in the cursor position
                                var line1 = doc1.getLine(cursor1.line);
                                var startChar1 = data.list[i].startChar;
                                
                                // Pruefung, ob die aktuelle Startposition wirklich ein Startchar ist... {...
                                if (startChar1 != null && line[data.from.ch])
                                {
                                    if (line[data.from.ch] != startChar1[0])
                                    {
                                        // Wenn eine Filtersuche durchgefuehrt wird, muss die die Beginnposition neu ermittelt werden:
                                        
                                        // ermittele Vorkommnisse von startChar1
                                        var elementsOfStartChar=line.split(startChar1);
                                        // gebe mir das letzte Element (LTR - links nach rechts gelesen)
                                        var lastOccurence = elementsOfStartChar[elementsOfStartChar.length-1];
                                        // Gebe die Position im String zuruck
                                        var posInLine = line1.indexOf(lastOccurence);
                                        var newFrom = posInLine - startChar1.length;

                                        // ueberschreiben von newBegin
                                        newBegin = CodeMirror.Pos(cur.line, newFrom);
                                    }
                                }
                                // ...}

								pick.apply(this, arguments);
								
								// Markierung setzen und Text ersetzen {...
								var newToChar = CodeMirror.Pos(cur.line, start - 1).ch + completion.text.length + 1;

                                var cssClassName    = "mgObjectHighlight";
                                var div = createDivBlock(cssClassName, data.list[i].link, completion.displayText, data.list[i].tooltip, "target='_blank'");

								var marker = cm.markText(newBegin, CodeMirror.Pos(cur.line, newToChar),
								{
									atomic: true,
									replacedWith: div,
								});
								// ...}
                            }

                            if (currentStartChar=="###")
                            {
                                if (filterCriteria != null && filterCriteria != "")
                                {
                                    console.log("---> if (filterCriteria != null && filterCriteria != ");
                                }
                                var startTyping=filterCriteria;
                                var listCallFromValue=value[i].listCallback(startTyping, currentStartChar);
                            }
                            else if (currentStartChar=="+++")
                            {
                                var listCallFromValue=value[i].listCallback("", currentStartChar);
                            }
                            else
                            {
                                // Notloesung...
                                return;
                            }
                            // start - 1, da ansonsten 3 #-Zeichen immer gesetzt wurden im Hintergrund.
                            return {
                                // list: value[i].listCallback(),
                                list: listCallFromValue,
                                from: CodeMirror.Pos(cur.line, start - 1),
                                to: CodeMirror.Pos(cur.line, end)
                            };
                        }
                    });

                }
            }
        });
    });
});

function createDivBlock(cssClassName, link, text, tooltip, target)
{
    var div = document.createElement('block');
	div.className = cssClassName;
	div.innerHTML = "<a href='" + link + "' " + target + " >" + text + "</a>";
	div.title = tooltip;
    return div;
}

function doMarkerOverlay(cm)
{
    var identifierBracket = "###";

    cm.eachLine(
            function(line) 
                { 
                    var currentLineText = line.text;
                    var textLength      = Number(currentLineText.length);
                    var beginnerChar    = -1;
                    var endingChar      = -1;
                    var cssClassName    = "mgObjectHighlight";

					// Markierung setzen und Text ersetzen {...
                    // var displayList = getDataByIdentifier("dummy");
                    // var div = createDivBlock(cssClassName, displayList.link, displayList.displayText, displayList.tooltip);

					var str = currentLineText; 

					var startToken	= "##@";
						endToken	= "@##";

					var listfrom 	= {}
						listto		= {};

					var patt1 = /##@/g;
					var i=0;
					while (patt1.test(str) == true) 
						{
							i++;
							var start = patt1.lastIndex;
							listfrom[i] = start - startToken.length;
						};
						
					var patt2 = /@##/g;
					var i=0;
					var maxTo;
					while (patt2.test(str) == true) 
						{
							i++;
							maxTo=i;
							var end = patt2.lastIndex;
							listto[i] = end;
						};

					for (i=0; i<=maxTo; i++)
                    {
                        beginnerChar=listfrom[i];
                        endingChar=listto[i];

                        identifier=currentLineText.substring(beginnerChar,endingChar).split("@")[1].split("|")[2];

                        var displayList = getDataViaAjaxByIdentifier(identifier);
                        
                        var div = createDivBlock(cssClassName, displayList.link, displayList.displayText, displayList.tooltip);

                        var marker = cm.markText({line: line.lineNo(), ch: beginnerChar}, {line: line.lineNo(), ch: endingChar},
                            {
                                atomic: true,
                                replacedWith: div,
                            });
                    }
					// ...}

					// wieder aufräumen
					beginnerChar    = -1;
					endingChar      = -1;
                } 
    )
}

// Method for calling the backend data
function getData(url)
{
	url += "&p_scope=" + $(p_scope_field).val();
	
	$.ajax({
            url: url,
            async: false,
            callback: 'callback',
            crossDomain: true,
            //contentType: 'application/json; charset=utf-8',
            contentType: 'application/json',
            type: 'GET',
            dataType: 'json',
            timeout: 5000,
            success: function (data, status) {
               if(data != undefined && data.post != undefined){
                //  $('#info').append('<div>' + data + '</div><hr/>');
                }
				returnData=data;
            },
            error: function () {
              alert('There was an error loading the data. ErrNo 001.');
            }
          });
	return returnData;
}

function getDataStaticError()
{
    var returnValue = 
    {
        displayText: "[Error]",
        link: "[Error]",
        tooltip: "[Error]",
        filterSendFromClient: "[Error]",
        startChar: "[Error]"
    };
  
    return returnValue;
}

// Get all data
function getDataViaAjax()
{
	url=getBaseURL() + 'getallobjects';
    var returnData = getData(url);
	return returnData;
}

// Get filtert data
function getDataViaAjaxByFilter(filter, startChar)
{
	var decodedStartChar = "";
    if (startChar!=null && startChar!="")
    {
        for (i=0;i<startChar.length;i++)
        {
            decodedStartChar += startChar.charCodeAt(i) + ";";
        }
    }

    url=getBaseURL() + 'getobjectsbyfilter&filter='+ filter + '&startChar=' +decodedStartChar;
    var returnData = getData(url);
	return returnData;
}

// Get data for initial load for marking
function getDataViaAjaxByIdentifier(identifier)
{
	url=getBaseURL() + 'getobjectbyidentifier&identifier='+ identifier;
    var returnData = getData(url);
    for (var key in returnData) { // immer nur 1 Eintrag
        returnValue = 
        {
            displayText: returnData[key].displayText,
            link: returnData[key].link,
            tooltip: returnData[key].tooltip
        };
    }
	return returnValue;
}
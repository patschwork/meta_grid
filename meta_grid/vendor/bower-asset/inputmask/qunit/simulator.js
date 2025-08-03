/* eslint-disable no-fallthrough */
import { keys } from "../lib/keycode";

export default function ($, Inputmask) {
  $.caret = function (input, begin, end) {
    input = input.nodeName ? input : input[0];
    input.focus();
    let range;
    if (typeof begin === "number") {
      end = typeof end === "number" ? end : begin;
      // if (!$(input).is(":visible")) {
      // 	return;
      // }

      if (input.setSelectionRange) {
        input.setSelectionRange(begin, end);
      } else if (window.getSelection) {
        range = document.createRange();
        if (input.firstChild === undefined) {
          const textNode = document.createTextNode("");
          input.appendChild(textNode);
        }
        range.setStart(
          input.firstChild,
          begin < input.value.length ? begin : input.value.length
        );
        range.setEnd(
          input.firstChild,
          end < input.value.length ? end : input.value.length
        );
        range.collapse(true);
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
        // input.focus();
      } else if (input.createTextRange) {
        range = input.createTextRange();
        range.collapse(true);
        range.moveEnd("character", end);
        range.moveStart("character", begin);
        range.select();
      }
    } else {
      if ("selectionStart" in input && "selectionEnd" in input) {
        begin = input.selectionStart;
        end = input.selectionEnd;
      } else if (window.getSelection) {
        range = window.getSelection().getRangeAt(0);
        if (
          range.commonAncestorContainer.parentNode === input ||
          range.commonAncestorContainer === input
        ) {
          begin = range.startOffset;
          end = range.endOffset;
        }
      } else if (document.selection && document.selection.createRange) {
        range = document.selection.createRange();
        begin = 0 - range.duplicate().moveStart("character", -100000);
        end = begin + range.text.length;
      }
      /* eslint-disable consistent-return */
      return {
        begin,
        end
      };
      /* eslint-enable consistent-return */
    }
  };
  $.fn = $.fn || $.prototype;
  $.fn.SendKey = function (key, modifier) {
    const elem = this.nodeName ? this : this[0],
      origCode = key;
    elem.type = "text"; // force textinput to support caret fn

    function trigger(elem, evnt) {
      elem.focus();
      if ($ === window.jQuery) {
        $(elem).trigger(evnt);
      } else {
        if (document.createEvent) {
          elem.dispatchEvent(evnt);
        } else {
          elem.fireEvent("on" + evnt.eventType, evnt);
        }
      }
    }

    switch (key) {
      case keys.Home:
        if (modifier == undefined) {
          $.caret(this, 0);
          break;
        }
      case keys.End:
        if (modifier == undefined) {
          $.caret(this, elem.value.length);
          break;
        }
      case keys.ArrowLeft:
        if (modifier == undefined) {
          var pos = $.caret(this);
          $.caret(this, pos.begin - 1);
          break;
        }
      case keys.ArrowRight:
        if (modifier == undefined) {
          var pos2 = $.caret(this);
          $.caret(this, pos2.end + 1);
          break;
        }
      default:
        if (
          (window.Inputmask &&
            window.Inputmask.prototype.defaults.inputEventOnly === true) ||
          (elem.inputmask && elem.inputmask.opts.inputEventOnly === true)
        ) {
          let input = new $.Event("input"),
            currentValue =
              elem.inputmask && elem.inputmask.__valueGet
                ? elem.inputmask.__valueGet.call(elem)
                : elem.value,
            caretPos = $.caret(elem),
            // caretOffset = 0,
            // console.log("initial " + currentValue);
            // console.log(caretPos);

            front = currentValue.substring(0, caretPos.begin),
            back = currentValue.substring(caretPos.end),
            newValue = currentValue;

          switch (key) {
            case keys.Backspace:
              if (caretPos.begin === caretPos.end) {
                front = front.substr(0, front.length - 1);
              }
              newValue = front + back;
              // console.log("backspace " + newValue);
              break;
            case keys.Delete:
              if (origCode !== ".") {
                if (caretPos.begin === caretPos.end) {
                  back = back.slice(1);
                }
                newValue = front + back;
                break;
              }
            default:
              newValue = front + key[0] + back;
              //   caretOffset = front.length > 0 ? 1 : 0;
              break;
          }

          if (elem.inputmask && elem.inputmask.__valueSet) {
            elem.inputmask.__valueSet.call(elem, newValue);
          } else {
            elem.value = newValue;
          }
          $.caret(elem, newValue.length - back.length);
          trigger(elem, input);
        } else {
          const keydown = new $.Event("keydown"),
            keypress = new $.Event("keypress"),
            keyup = new $.Event("keyup");

          keydown.key = key;
          if (modifier == keys.Control) {
            keydown.ctrlKey = true;
          }
          trigger(elem, keydown);
          if (!keydown.defaultPrevented) {
            keypress.key = key;
            if (modifier == keys.Control) {
              keypress.ctrlKey = true;
            }
            trigger(elem, keypress);
            if (!keypress.defaultPrevented) {
              keyup.key = key;
              if (modifier == keys.Control) {
                keyup.ctrlKey = true;
              }
              trigger(elem, keyup);
            }
          }
        }
    }
  };
  if (!("append" in $.fn)) {
    $.fn.append = function (child) {
      const input = this.nodeName ? this : this[0];
      input.insertAdjacentHTML("beforeend", child);
    };
  }
  if (!("remove" in $.fn)) {
    $.fn.remove = function () {
      let input = this.nodeName ? this : this[0];
      if (input !== undefined && input !== null) {
        input.parentElement.removeChild(input);
        input = undefined;
      }
    };
  }
  if (!("val" in $.fn)) {
    $.fn.val = function (value) {
      const input = this.nodeName ? this : this[0];
      if (value !== undefined) {
        if (input.inputmask) {
          input.inputmask._valueSet(value, true);
          $(input).trigger("setvalue");
        } else {
          input.value = value;
        }
      }

      return input.value;
    };
  }

  $.fn.Type = function (inputStr) {
    const input = this.nodeName ? this : this[0],
      $input = $(input);
    inputStr.split("").forEach(function (lmnt, ndx) {
      $input.SendKey(lmnt);
    });
  };

  $.fn.paste = function (inputStr) {
    const input = this.nodeName ? this : this[0],
      $input = $(input);
    if (window.clipboardData) {
      window.clipboardData.setData("Text", inputStr);
    } else {
      $.data($input, "clipboard", inputStr);
      window.clipboardData = {
        getData: function () {
          window.clipboardData = undefined;
          return $.data($input, "clipboard");
        }
      };
    }

    $input.trigger("paste");
  };

  $.fn.input = function (inputStr, caretBegin, caretEnd) {
    const input = this.nodeName ? this : this[0];
    input.inputmask.__valueSet.call(input, inputStr);
    if (caretBegin !== undefined) {
      $.caret(input, caretBegin, caretEnd);
    }
    $(input).trigger("input");
  };
}

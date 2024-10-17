function getHistory() {
  return document.getElementById("history-value").innerText;
}

function printHistory(num) {
  document.getElementById("history-value").innerText = num;
}

function getOutput() {
  return document.getElementById("output-value").innerText;
}

function printOutput(num) {
  if (num == "") {
    document.getElementById("output-value").innerText = num;
  } else {
    document.getElementById("output-value").innerText = getFormattedNumber(num);
  }
}

function getFormattedNumber(num) {
  if (num == "-") {
    return "";
  }
  var n = Number(num);
  var value = n.toLocaleString("en");
  return value;
}

function reverseNumberFormat(num) {
  return Number(num.replace(/,/g, ""));
}

var operator = document.getElementsByClassName("operator");
for (var i = 0; i < operator.length; i++) {
  operator[i].addEventListener("click", function () {
    var output = getOutput();
    var history = getHistory();

    if (this.id == "clear") {
      printHistory("");
      printOutput("");
    } else if (this.id == "backspace") {
      var output = reverseNumberFormat(getOutput()).toString();
      if (output) {
        output = output.substr(0, output.length - 1);
        printOutput(output);
      }
    } else {
      // If output and history are empty, prevent unwanted overwrites
      output = output == "" ? "" : reverseNumberFormat(output);
      history = history + output;

      if (this.id == "=") {
        history = history.replace(/\^/g, "**");
        history = history.replace(/\mod/g, "%");
        var result = eval(history);
        printOutput(result);
        printHistory("");
      } else if (this.id == "%") {
        var n = reverseNumberFormat(getOutput());
        var percent = n / 100;
        printOutput(percent.toFixed(4));
      }
      // Modulus functionality
      else if (this.id == "mod") {
        history = history + "mod";
        printHistory(history);
        printOutput("");
      }
      // Power functionality
      else if (this.id == "power") {
        history = history + "^"; // Keep ^ for display, but will replace it in eval step
        printHistory(history);
        printOutput("");
      }
      // Open parenthesis (don't overwrite operators)
      else if (this.id == "open-paren") {
        history += "(";
        printHistory(history);
        printOutput("");
      }
      // Close parenthesis (don't overwrite operators)
      else if (this.id == "close-paren") {
        history += ")";
        printHistory(history);
        printOutput("");
      }
      // Handle other operators without overwriting
      else {
        if (output == "" && history != "") {
          // If the last character is an operator, don't add another operator
          if (isNaN(history[history.length - 1])) {
            history = history.substr(0, history.length - 1);
          }
        }
        history = history + this.id;
        printHistory(history);
        printOutput("");
      }
    }
  });
}

var number = document.getElementsByClassName("number");
for (var i = 0; i < number.length; i++) {
  number[i].addEventListener("click", function () {
    var output = reverseNumberFormat(getOutput());
    //if output is a number
    if (output != NaN) {
      output = output + this.id;
      printOutput(output);
    }
  });
}

let checkbox = document.querySelector("input[name=theme]");
checkbox.addEventListener("change", function () {
  if (this.checked) {
    document.documentElement.setAttribute("data-theme", "dark");
  } else {
    document.documentElement.setAttribute("data-theme", "light");
  }
});
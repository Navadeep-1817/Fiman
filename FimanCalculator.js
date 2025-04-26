document.addEventListener("DOMContentLoaded", function () {
    let display = document.getElementById("display");
    let buttons = document.querySelectorAll(".buttons button");
    let expression = "";
    let memory = 0;

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            let value = this.innerText;

            if (value === "C") {
                expression = "";
                display.value = "";
            } else if (value === "=") {
                try {
                    expression = expression
                        .replace(/pi/g, "Math.PI")
                        .replace(/sqrt/g, "Math.sqrt")
                        .replace(/\^/g, "**")
                        .replace(/log/g, "Math.log10")
                        .replace(/exp/g, "Math.exp")
                        .replace(/sin/g, "Math.sin")
                        .replace(/cos/g, "Math.cos")
                        .replace(/tan/g, "Math.tan");

                    expression = eval(expression).toString();
                    display.value = expression;
                } catch {
                    display.value = "Error";
                    expression = "";
                }
            } else if (value === "←") {
                expression = expression.slice(0, -1);
                display.value = expression;
            } else if (value === "M+") {
                memory = parseFloat(display.value) || 0;
            } else if (value === "M-") {
                memory = 0;
            } else if (value === "MR") {
                expression += memory;
                display.value = expression;
            } else if (value === "MC") {
                memory = 0;
            } else {
                expression += value;
                display.value = expression;
            }
        });
    });

    // Keyboard Support
    document.addEventListener("keydown", function (e) {
        let key = e.key;
        if (!isNaN(key) || "+-*/().".includes(key)) {
            expression += key;
        } else if (key === "Enter") {
            document.querySelector(".equal").click();
        } else if (key === "Backspace") {
            document.querySelector(".back").click();
        } else if (key === "Escape") {
            document.querySelector(".clear").click();
        }
        display.value = expression;
    });
});

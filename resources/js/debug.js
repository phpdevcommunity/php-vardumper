
document.addEventListener("DOMContentLoaded", (event) => {
    let toggler = document.querySelectorAll("#uniqId.__beautify-var-dumper .caret");
    let i;
    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function(element) {
            const target = this.getAttribute("data-target");
            const targetElem = document.querySelector(target);
            if (targetElem) {
                targetElem.classList.toggle("active");
                this.classList.toggle("caret-down");
            }
        });
    }
});

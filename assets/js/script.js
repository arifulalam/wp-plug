document.addEventListener("mouseup", function (event) {
  let selectedText = window.getSelection().toString();
  if (selectedText.length > 0) {
    jQuery.ajax({
      method: "GET",
      url: "https://api.api-ninjas.com/v1/dictionary?word=" + selectedText,
      headers: { "X-Api-Key": "+Wd9yh1KKWvhBxKdguqqIA==c59wPPGBpa3ww1K6" },
      contentType: "application/json",
      success: function (result) {
        console.log(result);
        let dialog = `<dialog style='width: 500px; min-height: 150px; max-height: 350px; overflow: auto; border-radius: 10px; padding: 20px; background-color: #f9f9f9; box-shadow: 0 4px 8px rgba(0,0,0,0.2);'>
            <h2 style="text-decoration: underline; font-size: 15px; margin-top: 0;">${result.word}</h2>
            <p style="font-size: 13px">${result.definition}</p>
            <form method="dialog">
              <button id="close" onclick="document.querySelector('dialog').close()">OK</button>
            </form>
          </dialog>`;

        document.body.insertAdjacentHTML("beforeend", dialog);
        document.querySelector("dialog").showModal();
        document.querySelector("dialog").addEventListener("close", function () {
          this.remove();
        });
      },
      error: function ajaxError(jqXHR) {
        console.error("Error: ", jqXHR.responseText);
      },
    });
  }
});

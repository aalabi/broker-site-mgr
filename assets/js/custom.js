let development = true;
let url = development ? "http://localhost/kallista/" : "https://xalabi.com/";

let confirmBtn = document.getElementsByClassName("al-delete-confirm");
for (let index = 0; index < confirmBtn.length; index++) {
  confirmBtn[index].addEventListener(
    "click",
    function (event) {
      if (!confirm("Are you sure you want to delete")) {
        event.preventDefault();
      }
    },
    false
  );
}

let percentContainer = document.getElementById("percentContainer");
let addBtn = document.getElementById("al-add");
if (addBtn) {
  addBtn.addEventListener(
    "click",
    function (event) {
      let divRow = document.createElement("div");
      divRow.setAttribute("class", "row mb-5");

      let divCol7 = document.createElement("div");
      divCol7.setAttribute("class", "col-7");
      let select = document.createElement("select");
      select.required = true;
      select.setAttribute("name", "percentSc[]");
      select.setAttribute("class", "form-control");
      scs().forEach((aSc) => {
        option = document.createElement("option");
        option.value = `${aSc[1]}`;
        option.append(document.createTextNode(`${aSc[0]}`));
        select.append(option);
      });
      divCol7.appendChild(select);

      let divCol3 = document.createElement("div");
      divCol3.setAttribute("class", "col-3");
      let input = document.createElement("input");
      input.setAttribute("name", "percentValue[]");
      input.setAttribute("type", "number");
      input.setAttribute("min", "0");
      input.setAttribute("max", "100");
      input.setAttribute("step", "0.01");
      input.setAttribute("class", "form-control");
      divCol3.appendChild(input);

      let divCol2 = document.createElement("div");
      divCol2.setAttribute("class", "col-2");
      let btn = document.createElement("button");
      btn.setAttribute("type", "button");
      btn.setAttribute("class", "btn btn-danger al-rm-btn");
      btn.appendChild(document.createTextNode("-"));
      divCol2.appendChild(btn);
      btn.addEventListener(
        "click",
        function (e) {
          btn.parentElement.parentElement.remove();
        },
        false
      );

      divRow.appendChild(divCol7);
      divRow.appendChild(divCol3);
      divRow.appendChild(divCol2);
      percentContainer.appendChild(divRow);
    },
    false
  );
}

let scContainer = document.getElementById("scContainer");
let addScBtn = document.getElementById("al-addSc");
if (addScBtn) {
  addScBtn.addEventListener(
    "click",
    function (event) {
      let divRow = document.createElement("div");
      divRow.setAttribute("class", "row mb-5");

      let divCol7 = document.createElement("div");
      divCol7.setAttribute("class", "col-7");
      let select = document.createElement("select");
      select.required = true;
      select.setAttribute("name", "sc[]");
      select.setAttribute("class", "form-control");
      scs().forEach((aSc) => {
        option = document.createElement("option");
        option.value = `${aSc[1]}`;
        option.append(document.createTextNode(`${aSc[0]}`));
        select.append(option);
      });
      divCol7.appendChild(select);

      let divCol4 = document.createElement("div");
      divCol4.setAttribute("class", "col-4");
      let selectAddRm = document.createElement("select");
      selectAddRm.setAttribute("name", "addRemove[]");
      selectAddRm.setAttribute("class", "form-control");
      addRmOptions().forEach((aSc) => {
        option = document.createElement("option");
        option.value = `${aSc[1]}`;
        option.append(document.createTextNode(`${aSc[0]}`));
        selectAddRm.append(option);
      });
      divCol4.appendChild(selectAddRm);

      let divCol1 = document.createElement("div");
      divCol1.setAttribute("class", "col-1");
      let btn = document.createElement("button");
      btn.setAttribute("type", "button");
      btn.setAttribute("class", "btn btn-danger al-rm-btn");
      btn.appendChild(document.createTextNode("-"));
      divCol1.appendChild(btn);
      btn.addEventListener(
        "click",
        function (e) {
          btn.parentElement.parentElement.remove();
        },
        false
      );

      divRow.appendChild(divCol7);
      divRow.appendChild(divCol4);
      divRow.appendChild(divCol1);
      scContainer.appendChild(divRow);
    },
    false
  );
}

let deEntityContainer = document.getElementById("deEntityContainer");
let addDeEntityBtn = document.getElementById("al-addDeEntity");
if (addDeEntityBtn) {
  addDeEntityBtn.addEventListener(
    "click",
    function (event) {
      let divRow = document.createElement("div");
      divRow.setAttribute("class", "row mb-5");

      let divCol7 = document.createElement("div");
      divCol7.setAttribute("class", "col-7");
      let select = document.createElement("select");
      select.required = true;
      select.setAttribute("name", "deEntity[]");
      select.setAttribute("class", "form-control");
      select.required = true;
      deEntity().forEach((aDeEntity) => {
        option = document.createElement("option");
        option.value = `${aDeEntity[1]}`;
        option.append(document.createTextNode(`${aDeEntity[0]}`));
        select.append(option);
      });
      divCol7.appendChild(select);

      let divCol4 = document.createElement("div");
      divCol4.setAttribute("class", "col-4");
      let inputAccountNo = document.createElement("input");
      inputAccountNo.setAttribute("name", "deEntityAccountNo[]");
      inputAccountNo.setAttribute("class", "form-control");
      inputAccountNo.setAttribute("type", "text");
      inputAccountNo.setAttribute("placeholder", "id");
      divCol4.appendChild(inputAccountNo);

      let divCol1 = document.createElement("div");
      divCol1.setAttribute("class", "col-1");
      let btn = document.createElement("button");
      btn.setAttribute("type", "button");
      btn.setAttribute("class", "btn btn-danger al-rm-btn");
      btn.appendChild(document.createTextNode("-"));
      divCol1.appendChild(btn);
      btn.addEventListener(
        "click",
        function (e) {
          btn.parentElement.parentElement.remove();
        },
        false
      );

      divRow.appendChild(divCol7);
      divRow.appendChild(divCol4);
      divRow.appendChild(divCol1);
      deEntityContainer.appendChild(divRow);
    },
    false
  );
}

let removeBtn = document.getElementsByClassName("al-rm-btn");
for (let index = 0; index < removeBtn.length; index++) {
  removeBtn[index].addEventListener(
    "click",
    function (event) {
      this.parentElement.parentElement.remove();
    },
    false
  );
}

let removeSc = document.getElementsByClassName("al-btn-rmper");
for (let index = 0; index < removeSc.length; index++) {
  removeSc[index].addEventListener(
    "click",
    async function (event) {
      let scId = this.getAttribute("data-sc");
      if (confirm("Are you sure you want to delete")) {
        await fetch(url + "local-api/remove-sc.php?id=" + scId)
          .then((response) => {
            return response.json();
          })
          .then((data) => {
            console.log(data.deleted);
            if (data.deleted) {
              this.parentElement.remove();
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    false
  );
}

function addRmOptions() {
  return [
    ["add", "add"],
    ["remove", "remove"],
  ];
}

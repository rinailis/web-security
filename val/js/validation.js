const field = document.querySelectorAll(".field");
let result = true;
let inputMail = document.querySelector("#email");
let inputPassword = document.querySelector("#password");
let inputPhone = document.querySelector("#phone");
let inputBirth = document.querySelector("#birth");
let inputGender = document.querySelector("#gender");
let inputUsername = document.querySelector("#username");
let inputImage = document.querySelector("#image");
// маска для телефона
const phoneMask = IMask(inputPhone, {
  mask: "+{7}(000)000-00-00",
});
const currentDate = new Date();
let message = "";
let condition = "";

function valedation(form) {
  function removeError(field) {
    const parent = field.parentNode;

    if (field.classList.contains("error")) {
      parent.querySelector(".span-error").remove();
      field.classList.remove("error");
    }
  }

  function createError(field, text) {
    const parent = field.parentNode;

    if (!parent.querySelector("span")) {
      const errorSpan = document.createElement("span");
      errorSpan.classList.add("span-error");

      errorSpan.textContent = text;
      field.classList.add("error");

      parent.append(errorSpan);
    }
  }

  function processingValidation(regul, field, variables, textError) {
    form.querySelectorAll(`#${field}`).forEach((variables) => {
      if (!regul.test(variables.value)) {
        createError(variables, `*${textError}`);
        result = false;
      }
    });
  }

  form.querySelectorAll(".field").forEach((field) => {
    removeError(field);
    if (field.value == "") {
      createError(field, "*заполните поле");
      result = false;
    } else {
      // form.querySelectorAll("#email").forEach((inputMail) => {
      //   if (!reg.test(inputMail.value)) {
      //     createError(inputMail, "*некорректный адрес эл.почты");
      //     result = false;
      //   } else {
      //     result = true;
      //   }
      // });

      // проверка email
      let regMail =
        /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
      processingValidation(
        regMail,
        "email",
        inputMail,
        "некорректный адрес эл.почты"
      );

      // проверка пароля
      form.querySelectorAll("#password").forEach((inputPassword) => {
        const currentMonth = new Date()
          .toLocaleString("en", { month: "long" })
          .toLowerCase();
        const regSumbol = /(?=(.*\W){2,})/;
        const regSumbolRepeat = /(.)\1/;
        const regSumbolLanguage = /(?=.*[A-Za-z])(?=.*[А-Яа-я]).*/;
        const disallowedPasswords = [
          "123456",
          "password",
          // "123456789",
          "qwerty",
          "12345678",
          "111111",
          // "1234567890",
          // "1234567",
          "password1",
          "12345",
        ];
        disallowedPasswords.forEach((disallowedPassword) => {
          if (inputPassword.value.toLowerCase().includes(disallowedPassword)) {
            disallowedPasswords.value = true;
          }
        });
        if (inputPassword.value.length < 8) {
          createError(inputPassword, "*Пароль должен быть не менее 8 символов");
          result = false;
        } else if (disallowedPasswords.value) {
          createError(
            inputPassword,
            "*Пароль не должен содержать часто повторяющиеся комбинации"
          );
          result = false;
        } else if (!inputPassword.value.toLowerCase().includes(currentMonth)) {
          createError(
            inputPassword,
            "*Пароль должен содержать название текущего месяца на английском"
          );
          result = false;
        } else if (regSumbolRepeat.test(inputPassword.value)) {
          createError(
            inputPassword,
            "*Пароль не должен содержать повторяющиеся подряд символы"
          );
          result = false;
        } else if (!regSumbol.test(inputPassword.value)) {
          createError(
            inputPassword,
            "*Пароль должен содержать минимум 2 спец символа"
          );
          result = false;
        } else if (!regSumbolLanguage.test(inputPassword.value)) {
          createError(
            inputPassword,
            "*В пароле должна использоваться рус. и анг. раскладка"
          );
          result = false;
        } else {
          result = true;
        }
      });

      // проверка даты рождения
      form.querySelectorAll("#birth").forEach((inputBirth) => {
        console.log(
          "Пример: Jan 1, 1970 или  1970,1,1 или  70/01/01 или 04 Dec 1995"
        );
        const inputDate = new Date(inputBirth.value);
        window.years = currentDate.getFullYear() - inputDate.getFullYear();
        if (isNaN(Date.parse(inputDate))) {
          createError(
            inputBirth,
            "*Не корректный формат даты. Пример даты: Jan 1, 1970 или  1970,1,1 или  70/01/01 или 04 Dec 1995"
          );
          result = false;
        } else if (inputDate > currentDate) {
          createError(
            inputBirth,
            "*Дата не должна быть больше сегодняшнего числа"
          );
          result = false;
        } else if (window.years > 111) {
          createError(inputBirth, "*Дата рождения не должна превышать 111 лет");
          result = false;
        } else {
          result = true;
        }
      });

      // проверка пола
      form.querySelectorAll("#gender").forEach((inputGender) => {
        let genderAllowed = ["мальчик", "девочка", "м", "д"];
        if (window.years > 18) {
          genderAllowed = ["мужчина", "женщина", "м", "ж"];
        }
        const textGender = genderAllowed.join(" или ");
        console.log(textGender);
        if (!genderAllowed.includes(inputGender.value.toLowerCase())) {
          console.log("vsdvsvsdv");
          createError(
            inputGender,
            `*Пол принимает только следующие значения: ${genderAllowed.join(
              " или "
            )}`
          );
          result = false;
        } else {
          result = true;
        }

        // if (!inputPassword.value.toLowerCase().includes(currentMonth)) {
        //   createError(
        //     inputBirth,
        //     "*Дата не должна быть больше сегодняшнего числа"
        //   );
        //   result = false;
        // } else if (currentDate.getFullYear() - inputDate.getFullYear() > 111) {
        //   createError(inputBirth, "*Дата рождения не должна превышать 111 лет");
        //   result = false;
        // } else {
        //   result = true;
        // }
      });

      // проверка username
      form.querySelectorAll("#username").forEach((inputUsername) => {
        const regNumber = /\d/;
        if (regNumber.test(inputUsername.value)) {
          createError(
            inputUsername,
            "*Имя пользователя не должно содержать цифры"
          );
          result = false;
        } else {
          result = true;
        }
      });

      // проверка картинки
      function validateImageInput(fileInput) {
        // Получаем информацию о выбранных файлах
        var files = fileInput.files;

        // Проверка наличия выбранных файлов
        if (files.length === 0) {
          createError(fileInput, "*Файл не выбран" + fileName);
            result = false;
        }

        // Проверка расширения файла, размера и имени
        for (var i = 0; i < files.length; i++) {
          var file = files[i];
          var fileName = file.name;
          var fileSize = file.size;
          var fileExtension = fileName.split(".").pop().toLowerCase();
          var fileNameNotExtension = fileName.split('.').slice(0, -1).join(''); // Имя без расширения

          // Проверка расширения файла
          var allowedExtensions = ["jpg", "jpeg", "png", "gif"];
          var maxSize = 1 * 1024 * 1024; // 1 MB
          if (allowedExtensions.indexOf(fileExtension) === -1) {
            createError(fileInput, "*Расширение файла недопустимо " + fileName);
            result = false;
          } else if (fileSize > maxSize) {
            createError(fileInput, "*Максимальный размер файла 1MB");
            result = false;
          } else if (fileNameNotExtension.length < 5 || fileNameNotExtension.length > 50) {
            createError(
              fileInput,
              "*Имя файла должно быть больше 5 символов и меньше 50"
            );
            result = false;
          } else {
            result = true;
          }
        }
      }
      validateImageInput(inputImage);
    }
  });

  return result;
}

function createNotification(message, condition) {
  const notification = document.createElement("div");
  notification.classList.add(`notification`, `notification-${condition}`);
  document.body.append(notification);

  notification.textContent = message;

  setTimeout(function () {
    notification.remove();
  }, 3000);
}

document.querySelectorAll(".add-form").forEach((i) => {
  i.addEventListener("submit", async function (event) {
    event.preventDefault();
    let formData = new FormData(this);
    if (valedation(this) == true) {
      let response = await fetch("/controllers/validation.php", {
        method: "POST",
        body: formData,
      });

      if (response.ok) {
        message = "Заявка успешно отправленна";
        condition = "succes";
        createNotification(message, condition);
      } else {
        message = "Произошла ошибка, заявка не отправлена.";
        condition = "error";
        createNotification(message, condition);
      }

      this.reset();
    }
  });
});

const title = document.querySelector("input[name=title]");
const title_length = document.querySelector("p.info_title_length");

title.addEventListener("input", (event) => {
    title_length.innerHTML =
        "Maksymalnie 255 znaków. <span class='current_title_length'>" +
        title.value.length +
        "/255</span>";
    if (title.value.length >= 255) {
        const length = document.querySelector("span.current_title_length");
        if (length.style.color !== "#eb4d4b") {
            length.style.color = "#eb4d4b";
            length.style.fontWeight = "700";
        }
    }
});

const excerpt = document.querySelector("textarea[name=excerpt]");
const excerpt_length = document.querySelector("p.excerpt_length");

excerpt.addEventListener("input", (event) => {
    excerpt_length.innerHTML =
        "Maksymalnie 510 znaków. <span class='current_excerpt_length'>" +
        excerpt.value.length +
        "/510</span>";
    if (excerpt.value.length >= 510) {
        const length = document.querySelector("span.current_excerpt_length");
        if (length.style.color !== "#eb4d4b") {
            length.style.color = "#eb4d4b";
            length.style.fontWeight = "700";
        }
    }
});

var toolbarOptions = [
    ["bold", "italic", "underline", "strike"],
    ["blockquote", "code-block"],
    ["image"],
    [
        { align: "" },
        { align: "center" },
        { align: "right" },
        { align: "justify" },
    ],
    [{ size: ["small", false, "large", "huge"] }],
];

window.quill = new Quill("#editor", {
    modules: {
        toolbar: toolbarOptions,
    },
    theme: "snow",
});

let hiddenArea = document.getElementById('hiddenArea');

quill.setContents(quill.clipboard.convert(hiddenArea.value));

quill.getModule("toolbar").addHandler("image", () => {
    selectLocalImage();
});

window.selectLocalImage = function () {
    const input = document.createElement("input");
    input.setAttribute("type", "file");
    input.setAttribute("accept", "image/*");
    input.click();

    input.onchange = () => {
        const file = input.files[0];

        if (/^image\//.test(file.type)) {
            imageHandler(file);
        } else {
            console.warn("You could only upload images.");
        }
    };
};

window.imageHandler = function (image) {
    const token = document.querySelector('input[name=_token]').value;
    let formData = new FormData();
    formData.append("image", image);
    formData.append("_token", token);

    let url = document.getElementById("content").dataset.imageUrl;

    fetch(url, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": token,
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.url) {
                insertToEditor(data.url, quill);
            }
        })
        .catch(error => console.error("Error:", error));
};

window.calculateReadTime = function () {
    const body = document.querySelector(".ql-editor").innerHTML;
    const token = document.querySelector('input[name=_token]').value;

    const formData = new FormData();
    formData.append('body', body);
    formData.append('_token', token);

    fetch("/dashboard/calculate-read-time", {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-TOKEN": token,
        },
    })
        .then(response => response.json())
        .then(data => {
            document.querySelector('.reading-time').innerHTML = data + " min";
        })
        .catch(error => {
            console.error("Error:", error);
            Toast.fire({
                icon: 'error',
                title: 'Błąd!'
            });
        });
};

window.insertToEditor = function (url, editor) {
    const range = editor.getSelection();
    editor.insertEmbed(range.index, "image", url);
};

window.submitEdit = false;

window.submitForm = function () {
    window.submitEdit = true;
    let hiddenArea = document.getElementById("hiddenArea");
    let qlEditor = document.querySelector(".ql-editor");

    hiddenArea.value = qlEditor.innerHTML;

    document.getElementById("form").submit();
};

document.querySelectorAll(".ql-picker").forEach((tool) => {
    tool.addEventListener("mousedown", function (event) {
        event.preventDefault();
        event.stopPropagation();
    });
});

var change_image = document.querySelector(".change_image");

change_image.addEventListener("click", function () {
    document.querySelector("#image").click();
});

window.loadFile = function (event) {
    const fileInput = event.target;

    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function () {
            const output = document.getElementById("output");

            const existingUl = document.querySelector('.post_container ul');
            if (file.size > 10 * 1024 * 1024) {
                if (!existingUl) {
                    const ulElement = document.createElement('ul');
                    const liElement = document.createElement('li');

                    liElement.textContent = 'Obraz jest za duży. Maksymalny rozmiar pliku to 10MB!';

                    ulElement.appendChild(liElement);

                    const postContainer = document.querySelector('.post_container');

                    postContainer.insertBefore(ulElement, postContainer.firstChild);
                }
                fileInput.value = '';
                return;
            } else {
                if (existingUl) {
                    existingUl.remove();
                }
            }

            output.src = reader.result;
            window.savePost(false);
        };

        reader.readAsDataURL(file);
    }
};

window.changeToCategory = function (event, id) {
    const selectedCategory = document.querySelector(".category-selected");
    const sourceStyles = window.getComputedStyle(event.target);
    const categoryInput = document.querySelector("input[name=category_id]");

    selectedCategory.style.setProperty('background-color', sourceStyles.backgroundColor);
    selectedCategory.style.setProperty('color', sourceStyles.color);
    selectedCategory.innerHTML = event.target.innerHTML;
    selectedCategory.style.border = "none";
    categoryInput.value = event.target.dataset.id;
}

let visibleCategories = true
window.categoriesToggle = function () {
    const categories = document.querySelector(".categories_list");
    const toggleButton = document.querySelector(".categories_extend");

    if (visibleCategories) {
        visibleCategories = false;
        toggleButton.innerHTML = 'Rozwiń <i class="fa-solid fa-chevron-down"></i>';
    } else {
        visibleCategories = true;
        toggleButton.innerHTML = 'Ukryj <i class="fa-solid fa-chevron-up"></i>';
    }

    categories.classList.toggle('active');
}

// window.scrollTo(0, 0);

const title = document.querySelector("input[name=title]");
const title_length = document.querySelector("p.info_title_length");

title.addEventListener("input", (event) => {
    title_length.innerHTML =
        "Maksymalnie 255 znaków. <span class='current_title_length'>" +
        title.value.length +
        "/255</span>";
    if (title.value.length >= 255) {
        const length = document.querySelector("span.current_title_length");
        if (length.style.color != "#eb4d4b") {
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
        if (length.style.color != "#eb4d4b") {
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

var quill = new Quill("#editor", {
    modules: {
        toolbar: toolbarOptions,
    },
    theme: "snow",
});

quill.pasteHTML($("#hiddenArea").val());

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
    var formData = new FormData();
    formData.append("image", image);
    formData.append("_token", $("input[name=_token]").val());

    var url = $("#content").data("imageUrl");

    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.url) {
                insertToEditor(response.url, quill);
            }
        },
    });
};

window.insertToEditor = function (url, editor) {
    const range = editor.getSelection();
    editor.insertEmbed(range.index, "image", url);
};

$("#form").on("submit", function () {
    $("#hiddenArea").val($(".ql-editor").html());
});
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
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById("output");
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};

window.scrollTo(0, 0);

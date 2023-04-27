$(document).ready(function () {
    let questionCounter = 0;

    $("#create-question").on("click", function () {
        const questionId = "question-" + questionCounter;

        const newQuestion = $(`
        <div class="card mb-4" id="${questionId}">
            <div class="card-header" id="${questionId}-heading">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#${questionId}-collapse" aria-expanded="true" aria-controls="${questionId}-collapse">
                        <i class="fas fa-question-circle"></i> Вопрос №${$("#questions .card").length + 1}
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm delete-question float-right" data-question-id="${questionId}">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </h2>
            </div>
        
            <div id="${questionId}-collapse" class="collapse" aria-labelledby="${questionId}-heading" data-bs-parent="#questions">
                <div class="card-body">
                    <div class="form-group">
                        <label for="${questionId}-title">Текст вопроса</label>
                        <textarea type="text" class="form-control" id="${questionId}-title" placeholder="Введите текст вопроса" required ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="${questionId}-description">Дополнительная часть к вопросу</label>
                        <textarea type="text" class="form-control" id="${questionId}-description" placeholder="Введите дополнительную часть к вопросу"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="${questionId}-type">Тип вопроса</label>
                        <select class="form-control type" id="${questionId}-type" required>
                            <option value="" disabled selected>Выберите тип</option>
                            <option value="open">Открытый</option>
                            <option value="closed">Закрытый</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input is-multiple" type="checkbox" id="${questionId}-is_multiple">
                            <label class="form-check-label" for="${questionId}-is_multiple">Несколько вариантов ответа</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input is-strict" type="checkbox" id="${questionId}-is_strict">
                            <label class="form-check-label" for="${questionId}-is_strict">Вопрос имеет правильный ответ</label>
                        </div>
                    </div>
                    <div id="${questionId}-answers" class="mt-4"></div>
                    <button type="button" class="btn btn-secondary add-answer">
                        <i class="fas fa-plus"></i> Добавить вариант ответа
                    </button>
                    <button type="button" class="btn btn-danger remove-answer">
                        <i class="fas fa-minus"></i> Убрать вариант ответа
                    </button>
                </div>
            </div>
        </div>
        `);

        $("#questions").append(newQuestion);
        questionCounter++;

        bindEventHandlers(questionId);
        updateQuestionUI(questionId);
    });

    function bindEventHandlers(questionId) {
        $("#" + questionId + "-type, #" + questionId + "-is_multiple, #" + questionId + "-is_strict").off("change").on("change", function () {
            updateQuestionUI(questionId);
        });

        $("#" + questionId + " .add-answer").off("click").on("click", function () {
            const answerId = `${questionId}-answer-${$("#" + questionId + "-answers .answer").length}`;

            const newAnswer = $(`
            <div class="form-group answer" id="${answerId}">
                <label for="${answerId}-text">Текст ответа</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="${answerId}-text" placeholder="Введите текст ответа">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <input class="form-check-input is-correct" type="${$("#" + questionId + "-is_multiple").is(":checked") ? "checkbox" : "radio"}" id="${answerId}-is_correct">
                            <label class="form-check-label" for="${answerId}-is_correct">Правильный ответ</label>
                        </div>
                    </div>
                </div>
            </div>
        `);

            // Если вопрос не имеет правильного ответа, то блокируем радиокнопки
            if (!$("#" + questionId + "-is_strict").is(":checked")) {
                newAnswer.find(".is-correct").prop("disabled", true);
            }

            $("#" + questionId + "-answers").append(newAnswer);
        });

        $("#" + questionId + "-is_strict").off("change").on("change", function () {
            const isStrict = $(this).is(":checked");

            // Если вопрос имеет правильный ответ, то разблокируем все радиокнопки
            if (isStrict) {
                $("#" + questionId + "-answers .is-correct").prop("disabled", false);
            } else {
                // Если вопрос не имеет правильного ответа, то блокируем радиокнопки и сбрасываем выбор
                $("#" + questionId + "-answers .is-correct").prop("checked", false).prop("disabled", true);
            }

            // Изменение типа варианта ответа при изменении состояния флажка "Вопрос имеет правильный ответ"
            $("#" + questionId + "-answers .is-correct").attr("type", isStrict ? ($("#" + questionId + "-is_multiple").is(":checked") ? "checkbox" : "radio") : "checkbox");
        });

        $("#" + questionId + "-is_multiple").off("change").on("change", function () {
            const isMultiple = $(this).is(":checked");
            $("#" + questionId + "-answers .is-correct").attr("type", isMultiple ? "checkbox" : "radio");
        });

        $("#" + questionId + " .delete-question").off("click").on("click", function () {
            const questionId = $(this).closest(".card").attr("id");
            deleteQuestion(questionId);
        });

        updateQuestionNumbers();
    }

    function updateQuestionUI(questionId) {
        const isOpen = $("#" + questionId + "-type").val() === "open";
        const isMultiple = $("#" + questionId + "-is_multiple").is(":checked");
        const isStrict = $("#" + questionId + "-is_strict").is(":checked");

        $("#" + questionId + "-is_multiple").closest(".form-check").toggle(!isOpen);
        $("#" + questionId + "-is_strict").closest(".form-check").toggle(!isOpen);

        $("#" + questionId + " .add-answer").toggle(!isOpen);
        $("#" + questionId + " .remove-answer").toggle(!isOpen);

        if (isOpen) {
            $("#" + questionId + "-answers").empty();
        }

        $("#" + questionId + " .is-correct").attr("type", isMultiple ? "checkbox" : "radio");

        if (!isStrict) {
            $("#" + questionId + " .is-correct").prop("checked", false).prop("disabled", true);
        } else {
            $("#" + questionId + " .is-correct").prop("disabled", false);
        }

        // Обновление атрибута 'required' для вариантов ответа при изменении типа вопроса
        $("#" + questionId + "-answers .answer input[type=text]").prop("required", !isOpen);

        // Обновление атрибута 'name' для радиокнопок в одном вопросе
        if (!isMultiple) {
            $("#" + questionId + "-answers .is-correct").attr("name", questionId + "-correct");
        } else {
            $("#" + questionId + "-answers .is-correct").removeAttr("name");
        }
    }

    function deleteQuestion(questionId) {
        $("#" + questionId).remove();
        updateQuestionNumbers();
    }

    function updateQuestionNumbers() {
        $("#questions .card").each(function (index, el) {
            const oldQuestionId = $(el).attr("id");
            const newQuestionId = `question-${index}`;
            $(el).find(".btn-link").text(`Вопрос №${index + 1}`);
            $(el).attr("id", newQuestionId);
            $(el).find(".card-header").attr("id", `${newQuestionId}-heading`);
            $(el).find(".collapse").attr("id", `${newQuestionId}-collapse`);
            $(el).find(".type").attr("id", `${newQuestionId}-type`).attr("name", `${newQuestionId}-type`);
            $(el).find(".is_multiple").attr("id", `${newQuestionId}-is_multiple`).attr("name", `${newQuestionId}-is_multiple`);
            $(el).find(".is_strict").attr("id", `${newQuestionId}-is_strict`).attr("name", `${newQuestionId}-is_strict`);
            $(el).find(".title").attr("id", `${newQuestionId}-title`).attr("name", `${newQuestionId}-title`);
            $(el).find(".description").attr("id", `${newQuestionId}-description`).attr("name", `${newQuestionId}-description`);

            $(el).find(".answer").each(function (answerIndex, answerEl) {
                const oldAnswerId = $(answerEl).attr("id");
                const newAnswerId = `${newQuestionId}-answer-${answerIndex}`;
                $(answerEl).attr("id", newAnswerId);
                $(answerEl).find(".text").attr("id", `${newAnswerId}-text`).attr("name", `${newAnswerId}-text`);
                $(answerEl).find(".is_correct").attr("id", `${newAnswerId}-is_correct`).attr("name", `${newAnswerId}-is_correct`);
                $(answerEl).find("label").attr("for", `${newAnswerId}-is_correct`);
            });
        });
    }

    function collectQuestionsData() {
        let questions = [];

        $("#questions .card").each(function () {
            const question = {};
            question.title = $(this).find(`#${$(this).attr('id')}-title`).val();
            question.description = $(this).find(`#${$(this).attr('id')}-description`).val();
            question.type = $(this).find(".type").val();
            question.is_multiple = $(this).find(`#${$(this).attr('id')}-is_multiple`).is(":checked");
            question.is_strict = $(this).find(`#${$(this).attr('id')}-is_strict`).is(":checked");

            const answers = [];
            $(this).find(".answer").each(function () {
                const answer = {};
                answer.text = $(this).find(`#${$(this).attr('id')}-text`).val();
                answer.is_correct = $(this).find(`#${$(this).attr('id')}-is_correct`).is(":checked");
                answers.push(answer);
            });

            question.answers = answers;
            questions.push(question);
        });

        return questions;
    }

    function renderQuestionsFromJSON(questions) {
        questions.forEach((questionData, index) => {
            let button = $("#create-question")[0];
            button.click();
            const questionId = `question-${index}`;
            fillQuestionData(questionId, questionData);
            bindEventHandlers(questionId);
            updateQuestionUI(questionId);
        });
    }

    function fillQuestionData(questionId, questionData) {
        $("#" + questionId + "-title").val(questionData.title);
        $("#" + questionId + "-description").val(questionData.description);
        $("#" + questionId + "-type").val(questionData.type);
        $("#" + questionId + "-is_multiple").prop("checked", questionData.is_multiple);
        $("#" + questionId + "-is_strict").prop("checked", questionData.is_strict);

        questionData.answers.forEach((answerData, index) => {
            $("#" + questionId + " .add-answer").trigger("click");
            const answerId = `${questionId}-answer-${index}`;
            $("#" + answerId + "-text").val(answerData.text);
            $("#" + answerId + "-is_correct").prop("checked", answerData.is_correct);
        });
    }

    $("#new-test-form").on("submit", function (e) {
        const questions = collectQuestionsData();

        // Количество вопросов в скрытое поле
        $("#test-questions_count").val(questions.length);

        // JSON строка с вопросами и ответами в скрытое поле
        $("#test-questions_raw").val(JSON.stringify(questions));
    });

    const questionsData = $("#test-questions_raw").val();
    if (questionsData) {
        const questions = typeof questionsData === 'string' ? JSON.parse(questionsData) : questionsData;
        renderQuestionsFromJSON(questions);
    }
})
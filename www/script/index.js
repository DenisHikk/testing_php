$("#sessionId").submit(function (e) {
    e.preventDefault();
    const data = new FormData();
    data.append('userId', $("#userId").val());
    data.append('command', 'update_session')
    ajax(data, (success)=>{console.log(`Успешно сохранено ${success}`)});
});


$("#formElem").submit(function (e) { 
    e.preventDefault();
    const data = new FormData();
    data.append('file', $("#fileCSV").prop('files')[0]);
    data.append('command', 'import');
    ajax(data, (success) => {
        alert("Импортировано: " + success.import + "\n" + "Обновленно: " + success.update);
    });
});

$("#formExport").submit(function (e) { 
    e.preventDefault();
    $.ajax({
        url: 'http://localhost:8080/update.php',  // Путь к вашему PHP-скрипту
        type: 'GET',
        success: function(response) {
            // Создаем ссылку для скачивания файла
            var link = document.createElement('a');
            link.href = 'http://localhost:8080/update.php';  // Тот же путь, что генерирует файл
            link.download = 'file.csv';  // Указываем имя файла для скачивания
            link.click();  // Имитируем клик для скачивания
        },
        error: function(xhr, status, error) {
            console.log('Ошибка при запросе: ' + error);
        }
    });
});


function ajax(data, successRepsonse = (success)=>{}) {
    $.ajax({
        type: "POST",
        url: "http://localhost:8080/update.php",
        data: data,
        contentType: false,
        processData: false,
        dataType: "json",
        success: successRepsonse
    });
}

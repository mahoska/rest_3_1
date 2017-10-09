var myAjax = {
    get: function(page, process) {
        process = process || 0;
        var request = new XMLHttpRequest();
        if (process != 0) {
            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    process(request.responseText);
                }
            }
        }
        request.open("GET", page, true);//true задает асинхронные запросы
        request.send();//Отсылает запрос
    },
    post: function(page, data, process) {
        process = process || 0;
        //определяет API, который обеспечивает клиентский скрипт 
        //функциональностью для обмена данными между клиентом и сервером
        //Объект XMLHttpRequest (или, сокращенно, XHR) дает возможность браузеру
        //делать HTTP-запросы к серверу без перезагрузки страницы.
        var request = new XMLHttpRequest();
        if (process != 0) {
            //Создает функцию, которая будет выполнена, когда будет получен ответ с сервера
            //Асинхронность включается третьим параметром функции open.
            // В отличие от синхронного запроса, функция send() не останавливает выполнение скрипта, 
            // а просто отправляет запрос.

//Запрос xmlhttp регулярно отчитывается о своем состоянии через вызов функции xmlhttp.onreadystatechange.
// Состояние под номером 4 означает конец выполнения, поэтому функция-обработчик 
// при каждом вызове проверяет - не настало ли это состояние.
            request.onreadystatechange = function() {
                if (request.readyState == 4) {//4 - Complete (выполнен)
                    process(request.responseText);//responseText - ответ с сервера
                }
            }
        }
        request.open("POST", page, true);//Посылает запрос на сервер,
        // в качестве адресата запроса выступает файл с серверным php-скриптом
        //true - включает асинхронность, т.е браузер не ждет выполнения запроса для продолжения скрипта.
        // Вместо этого к свойству onreadystatechange подвешивается функция, 
        // которую запрос вызовет сам, когда получит ответ с сервера.
        
        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
         //send(data)отсылает запрос. Аргумент - тело запроса. Например, GET-запроса тела нет, поэтому используется send(null), 
                //а для POST-запросов тело содержит параметры запроса.
        request.send(data);
    }
}


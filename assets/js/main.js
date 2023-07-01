
$(document).on("click", ".btn_send", function (e) {
    e.preventDefault();
    $("#result").removeClass('showResult');
    let days = parseInt($("#customRange2").val()); // Получаем значение поля "customRange1" и преобразуем его в число
    let currentDate = new Date(); // Получаем текущую дату
    let currentMonth = currentDate.getMonth(); // Получаем номер текущего месяца (от 0 до 11)
    let daysInMonth = new Date(currentDate.getFullYear(), currentMonth + 1, 0).getDate(); // Получаем количество дней в текущем месяце

    // Проверяем, что значение days является числом от 1 до daysInMonth
    if (isNaN(days) || days < 1 || days > daysInMonth) {
        $(".errorsInput").text("Введите число от 1 до " + daysInMonth);
        $("#customRange2").addClass('is-invalid');
        return; // Прерываем выполнение кода
    } else {
        $(".errorsInput").text("");
        $("#customRange2").removeClass('is-invalid');
        $("#customRange2").addClass('is-valid'); 
    }
    $(".loader").show();
    var data = $("#form").serialize(); 
    $.post("api/calculator.php", {data}, function (a) {
        $(".loader").hide();
        let parsedData  = JSON.parse(a);
        if (parsedData.errors) {
            $(".titleAlert").text(parsedData.errors);
            $("#alert").addClass("show");
        } else {
            console.log(parsedData);
            $("#result").addClass("showResult");
            $("#resultCalc").empty();
            $("#resultCalc").append(`
                <div class="col-lg-2 col-3 col-md-3 col-xl-4">
                    <div class="card popup" type="clip">
                        <div class="d-flex p-3">
                            <div class="">
                                <h2 class="my-3">
                                     <span style="cursor:pointer" title="">Продукт: ${parsedData.title}</span> 
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-3 col-md-3 col-xl-4">
                    <div class="card popup" type="clip">
                        <div class="d-flex p-3">
                            <div class="">
                                <h2 class="my-3">
                                     <span style="cursor:pointer" title="">Цена: ${parsedData.oldPrice} рублей</span> 
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-3 col-md-3 col-xl-4">
                    <div class="card popup" type="clip">
                        <div class="d-flex p-3">
                            <div class="">
                                <h2 class="my-3">
                                     <span style="cursor:pointer" title="Дней выбрано">Дней: ${parsedData.days}</span>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                ${Object.keys(parsedData).some(key => key.startsWith('dop-')) ? `
                    <div class="col-lg-12 col-12 col-md-12 col-xl-12 mt-4">
                        <div class="card popup" type="clip">
                            <div class="d-flex p-3">
                                <div class="">
                                    <h3>Дополнительные услуги:</h3>
                                    <h2 class="my-3">
                                        ${Object.keys(parsedData).map(key => {
                                            if (key.startsWith('dop-')) {
                                                const value = parsedData[key];
                                                const text = key === 'dop-1' ? 'Детское кресло' :
                                                             key === 'dop-2' ? 'Мойка авто' :
                                                             key === 'dop-3' ? 'Видеорегистратор' :
                                                             key === 'dop-4' ? 'Антирадар' :
                                                             'Дополнительная улуга';
                                                return `<p style="cursor:pointer" title="">${text} : ${value} * ${parsedData.days}</p>`;
                                            }
                                        }).join('')}
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>
                ` : ''}   
            ${parsedData.tarif !== 0 ? `
                <div class="col-lg-6 col-6 col-md-6 col-xl-6 mt-4">
                    <div class="card popup" type="clip">
                        <div class="d-flex p-3">
                            <div class="">
                                <span class="my-3">Активирован тариф от ${parsedData.tarif} дней цена за сутки снижена: ${parsedData.tarifPrice}</span>
                            </div>
                        </div>
                    </div>
                </div>
            ` : ''}
                <div class="col-lg-6 col-6 col-md-6 col-xl-6 mt-4">
                    <div class="card popup" type="clip">
                        <div class="d-flex p-3">
                            <div class="">
                                <h2 class="my-3">
                                     <span style="cursor:pointer; color: green;" title="">Итог: ${parsedData.price} рублей</span> 
                                </h2>
                            </div>
                        </div>
                    </div>
            </div>`);
        }
    });
});
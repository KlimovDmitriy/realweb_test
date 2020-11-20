# realweb_test
Тестовое задание на позицию Веб-разработчик<br/>
Создан компонент для размещения на любой странице с компонентом bitrix:news.detail
<h2>
Как добавить и параметры компонента
</h2>
<code>
<? $APPLICATION->IncludeComponent(
    "dklimov:rating",
    ".default",
    Array(
            "UPVOTE_CLASS"=>'up',
          "DOWNVOTE_CLASS"=>'down',  
        "ELEMENT_ID"=>$arResult['ID'],
        "IBLOCK_ID"=>$arResult['IBLOCK_ID'],
        "RATING_PROP_CODE"=>"RATING"
    ),
    false
);?>
</code>
<p> Где:
<ul>
<li>"UPVOTE_CLASS" - класс для кнопки с повышением рейтинга</li>
<li>"DOWNVOTE_CLASS" - класс для кнопки с понижением рейтинга</li>
<li>"ELEMENT_ID" - ID элемента ИБ</li>
<li>"IBLOCK_ID" - ID ИБ</li>
<li>"RATING_PROP_CODE" - символьный код свойства для подсчета</li>
</ul>
</p>
<h3>Выходные данные для шаблона</h3>
<ul>
    <li>$arResult['UPVOTE_CLASS'] - класс для кнопки с повышением рейтинга</li>
    <li>$arResult['DOWNVOTE_CLASS'] - - класс для кнопки с понижением рейтинга</li>
    <li>$arResult['RATING'] - Рейтинг новости</li>
    </ul>
<h2>
Проверка проголосовавших
</h2>
<p>Для проверки проголосовавших необходимо создать HL-блок с полями отвечающими за хранение IP-адреса(UF_IP) и ID элемента(UF_ELEMENT_ID) и указать его в rating/class.php:83</p>

## Пожалуйста, ознакомьтесь с Инструкцией на русском языке ниже
## Please read Readme in Russian language below

----------------------------
Extension: UncosNews
----------------------------
Version: 1.0-rc1
Since: March 24th, 2011
Author: Alexander "unglued" Matrosov <unglud@gmail.com> <http://unglued.ru/makingof/uncosnews/>
License: http://www.gnu.org/licenses/gpl-3.0.html GNU Public License v3

This is a very simple but very useful component for MODx Revolution lets you create on your site a simple page with the news.
All news are stored in a separate database table, and therefore will not interfere in the resource tree.
Simple and easy menu for adding and editing of news in the Components.
News can be displayed in any form, anywhere on the site like list of recent news or a single extended news.
UncosNews can also be used for just anything. For example, for a mini blog, or a list of anything useful for displaying quotes or sponsored links.
In any place on your site! Just add a call of snippet.

Snippet: [[!UncosNews]]
-----------------------
Params:
&tpl=`rowTpl` - Chunk for news lists
&fulltpl=`fullTpl` - Chunk for one full news item
&sort=`id` - Which field to sort news
(id:By ordinal, title: By title, desc: By short desc, text: By text, createdon: By creation date, editedon: By date editing)
&dir=`DESC` - Sort direction (ASC or DESC)
&limit=`0` - Number of news from news list (0 - no limit)
&newsPage=`` - ID News page, where is show full newsitem (default current page)
&expand=`true` - if set `0` - disables display the full news in this snippet call (useful if on the same page present news list and full news)
-----------------------------------------------------------
Placeholders:
[[+createdby]] - Author id
[[+user.fullname]] - Author fullname
[[+user.username]] - Author username (aka login)
[[+createdon:strtotime:date=`%d.%m.%Y`]] - creation date (with output filter for example, default format: YYYY-MM-DD)
[[+editedon:strtotime:date=`%d.%m.%Y`]] - date editing
[[+editedby]] - ID, who's edited news
[[+url]] - url to extended news (full)
[[+title]] - Title
[[+desc]] - Short description
[[+text]] - Full news item text
-------------------------------
Default chunk:
<div class="news_box">
	<div class="news_credits">Created by [[+user.fullname]] aka [[+user.username]] on [[+createdon:strtotime:date=`%d.%m.%Y`]]</div>
  <div class="news_title"><a href="[[+url]]">[[+title]]</a></div>
	<div class="news_desc">[[+desc]]</div>
  <div class="news_text">[[+text]]</div>
</div>
---------------------------------------
Examples:
Say you have a news page with ID 55 and the left column contains 3 fresh headlines.
Then for the withdrawal of the news stream in the column we call a snippet:
[[! UncosNews? & tpl = `NewsListTpl` & limit = `3`& newsPage =`55`& expand =`0`]]
Where &tpl - chunk to the news stream, &limit - the number of titles, &newsPage - news page, which will be displayed expanded news and &expand - the prohibition on the display of extended news in the left column.
For the news page with ID 55 call the snippet will be quite simple:
[[! UncosNews? & tpl = `FullNewsListTpl` & fulltpl = `FullNewsItemTpl`]]
Where &tpl - chunk to news feed on the news page, &fulltpl - chunk to the one expanded news item.


Thanks for using.


----------------------------
============================
----------------------------


Компонент: UncosNews
----------------------------
Версия: 1.0-rc1
Изготовлено: March 24th, 2011
Авор: Alexander "unglued" Matrosov <unglud@gmail.com> <http://unglued.ru/makingof/uncosnews/>
Лицензия: http://www.gnu.org/licenses/gpl-3.0.html Стандартная Общественная Лицензия GNU версии 3

Это очень простой но очень полезный компонент для MODx Revolution позволяет создавать у себя на сайте простую страницу с новостями.
Все новости хранятся в отдельной таблице базы данных, поэтому не будут мешаться в дереве ресурсов.
Простое и удобное меню для добавления и редактирования новостей находится в разделе Компоненты.
Новости можно выводить в любом виде и в любом месте на сайте в списком последних новостей или отдельной расширенной новостью.
UncosNews так же можно использовать для всего чего угодно. Например для мини блога, или списка чего-либо полезного, для отображения цитат или рекламных ссылок.
В любом месте вашего сайта! Просто добавьте вызов сниппета.

Вызов сниппета: [[!UncosNews]]
-----------------------
Параметры:
&tpl=`rowTpl` - Чанк для списка новостей
&fulltpl=`fullTpl` - Чанк для подной полной новости
&sort=`id` - Поле сортировки
(id:По порядковому номеру, title: по заголовку, desc: по описанию, text: по тексту, createdon: по дате создания, editedon: по дате редактирования)
&dir=`DESC` - направления сортировки (ASC или DESC)
&limit=`0` - Количество новостей в листе (0 - без ограничений)';
&newsPage=`` - ID страницы с новостями, где отображается полный текст новости (по умолчанию текущая страница)
&expand=`true` - если `0`, запрещает показывать полную новость в этом вызове сниппета (полезно, если на одной и той же странице находятся как лента так и развернутая новость)
-----------------------------------------------------------
Плейсхолдеры:
[[+createdby]] - ID автора
[[+user.fullname]] - Полное имя автора
[[+user.username]] - Имя автора (такой же как логин)
[[+createdon:strtotime:date=`%d.%m.%Y`]] - Дата создания (с фильтром для примера. по умолчанию дата выводится в формате YYYY-MM-DD)
[[+editedon:strtotime:date=`%d.%m.%Y`]] - Дата редактирования
[[+editedby]] - ID того, кто редактировал
[[+url]] - адрес полной новости
[[+title]] - Заголовок
[[+desc]] - Короткое описание (анонс)
[[+text]] - Полный текст новости
-------------------------------
Чанк, используемый по умолчанию:
<div class="news_box">
	<div class="news_credits">Created by [[+user.fullname]] aka [[+user.username]] on [[+createdon:strtotime:date=`%d.%m.%Y`]]</div>
  <div class="news_title"><a href="[[+url]]">[[+title]]</a></div>
	<div class="news_desc">[[+desc]]</div>
  <div class="news_text">[[+text]]</div>
</div>
---------------------------------------
Примеры:
Предположим у вас есть страница новостей с ID 55 и в левой колонке находится 3 свежих новостных заголовка.
Тогда для вывода новостной ленты в колонке мы вызовем сниппет:
[[!UncosNews? &tpl=`NewsListTpl` &limit=`3` &newsPage=`55` &expand=`0`]]
Где tpl - чанк для новостной ленты, limit - количество заголовков, newsPage - страница новостей, где будут отображаться расширенные новости и expand - запрет на отображение расширенных новостей в левой колонке.
Для страницы новостей с ID 55 вызов сниппета будет совсем простой:
[[!UncosNews? &tpl=`FullNewsListTpl`&fulltpl=`FullNewsItemTpl`]]
Где tpl - чанк для новостной ленты на странице новостей, fulltpl - чанк для одной развернутой новости.



Alexander "unglued" Matrosov
unglud@gmail.com
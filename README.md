### Кроки для старту сервiсу

```
docker-compose up -d
```

### Кроки для запуску тестiв

```
docker-compose exec php bin/phpunit
```

### Опис API

Усі ендоїнти реалізовані як того вимагаю завдання.

### Mетодологію, яку ви обрали, та пояснення, чому на ваш погляд вона найкраще підходить

#### Алгоритм поиска отличий

Для поиска разницы между двумя текстами была выбрана метрика расстояния Левенштейна.
Имплементация алгоритма самая простая с матрицей m на n, сложностью 0(M*N). Был также рассмотрен
алгоритм итерации с матрицей 2 * M, однако временные затраты увеличиваются в связи с особенностями PHP.

#### Структура сущности статьи в БД 

Операция поиска расстояния Левенштейна между двумя текстами весьма затратна, потому для оптимизации поиска дубликатов 
для текста была реализована следующая структура.
1. Текст разбивается на токены(слова) и нахождение разницы сокращается с длины набора символов до длины набора токенов.
`['red', 'fox', 'jumpms', 'over', 'brown', 'fox']`
2. При добавлении текста в БД также добавляется его длина токенов. 
3. Также для каждого текста в БД добавляется словарь токенов сортированный по убыванию 
`['fox' => 2, 'red' => 1, 'jumps' => 1, ...]`

#### Поиск дубликатов:

При добавлении, тест разбивается на токены и находится их длина и словарь. 

Выполняется запрос в БД для поиска потенциальных дубликатов, который включает 2 этапа сортировки:
1. Длина токенов дубликата(X) должна совпадать на 95% с исходной(Y). 0.95*Y < X < 1.05*Y
2. Итерирование исходного словаря до тех пор пока разница с токенами в БД не перевалит за порог. 
Этот метод отбросит 
(реализованно js функцией [dictionary_diff](docker/mongo/mongo-init.js) в MongoDB)

Когда получен список потенциальных дубликатов - итеративно сравниваются набор токенов каждого из них с набором
токенов исходного текста, и определяется расстояние Левенштейна. Если расстояние не удовлетворяет порогу 
входимости - потенциальный дубликат удаляется. Всем остальным добавляется ID исходной статьи как дубликат, а исходной статье
добавляется список всех дубликатов.

#### Стемминг

При токенизации текста реалихован алгоритм нормализациии токенов. Для этого используется [inflections.csv](docker/inflections/inflections.csv)
файл с парами (base form, inflected) загружаемая в MongoDB. БД инфлексий взята c LanguageTool.

### Pечі, на які ви б хотіли звернути увагу або наступнi кроки для вдосконалення вашого сервiсу 

Алгоритм поиска расстояния между двумя текстами должен быть оптимизирован или вынесен в отдельный сервис.


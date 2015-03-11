The database object can be accessed from anywhere via:

```php
$db = App::getInstance()->getDBO();
```
or

```php
$db = App::getDBO();
```
or inside a model like so

```php
$db = $this->getDBO();
```
***

### Running Queries
Query can be either a string or a **Query** object. To submit a query use the following function:

```php
$db = $this->getDBO();
$query = "SELECT * FROM #__mytable";
$db->setQuery($query);
$items = $db->loadObjectList();
```
or

```php
$db = $this->getDBO();
$query = $db->getQuery(true);
$query->select('*')
    ->from('#__mytable');
$db->setQuery($query);
$items = $db->loadObjectList();
```

***
### Insert
Inserting a new record can be done in several ways:

**Old style query**

```php
$db = $this->getDBO();
$query = "INSERT INTO #__mytable (col1, col2) VALUES('value1', 'value2')";
$db->setQuery($query);
$db->execute();
```

**Query object**

```php
$db = $this->getDBO();
$query = $db->getQuery(true);
$query->insert("#__mytable")
    ->set("col1 = 'value1'")
    ->set("col2 = ".$db->quote('value2'); // in this case the value is escaped and quoted
$db->setQuery($query);
$db->execute();
```

**PHP object**

```php
$db = $this->getDBO();
$item = new stdClass();
$item->col1 = 'value1';
$item->col2 = 'value2';
$db->insertObject("#__mytable", $item, 'id');
```
***

### Insert multiple records
Inserting multiple records at once:

```php
$db = $this->getDBO();

$items = array();

$item1 = new stdClass();
$item1->col1 = 'value1';
$item1->col2 = 'value2';
$items[] = $item1;

$item2 = new stdClass();
$item2->col1 = 'value1';
$item2->col2 = 'value2';
$items[] = $item2;

$db->insertObjects("#__mytable", $items);
```

***
### Update
Updating a record can be done in several ways

**Old style query**

```php
$db = $this->getDBO();
$query = "UPDATE #__mytable SET col1 = 'value1', col2 = 'value2'";
$db->setQuery($query);
$db->execute();
```

**Query object**

```php
$db = $this->getDBO();
$query = $db->getQuery(true);
$query->update("#__mytable")
    ->set("col1 = 'value1'")
    ->set("col2 = ".$db->quote('value2'); // in this case the value is escaped and quoted
$db->setQuery($query);
$db->execute();
```

**PHP object**

```php
$db = $this->getDBO();
$item = new stdClass();
$item->col1 = 'value1';
$item->col2 = 'value2';
$db->updateObject("#__mytable", $item, 'id');
```

***
### Delete
Deleting a record can be done in two ways:

**Old style query**

```php
$db = $this->getDBO();
$query = "DELETE #__mytable WHERE col2 = 'value2'";
$db->setQuery($query);
$db->execute();
```

**Query object**

```php
$db = $this->getDBO();
$query = $db->getQuery(true);
$query->delete("#__mytable")
    ->where("col1 = 'value1'");
$db->setQuery($query);
$db->execute();
```

***
### Get number of total records when using LIMIT or OFFSET
By setting the last parameter of the method select() to true it will prepend **SQL_CALC_FOUND_ROWS** to the query

```php
$db = $this->getDBO();
$query = $db->getQuery(true);
$query->select('*', true)
    ->from('#__mytable');
$db->setQuery($query);
$items = $db->loadObjectList();
$total = $db->getTotalCount();
```
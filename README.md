# Yii Database Fail Over
If database connection fails, it will try to connect to other fail over connections.
Usage
--------------------------
Your components in main.php. In this case if db connection fails, it will try to connect to db3 first, if db3 fails, it will try db4.
```php
'db' => array(
  'class' => 'application.components.FoDbConnection',
  'failOverConnections' => ['db3', 'db4'],
  'connectionString' => '...',
  'username' => '...',
  'password' => '...',
),
'db3' => array(
  'class' => 'application.components.FoDbConnection',
  'connectionString' => '...',
  'username' => '...',
  'password' => '...',
),
'db4' => array(
  'class' => 'application.components.FoDbConnection',
  'connectionString' => '...',
  'username' => '...',
  'password' => '...',
),
```

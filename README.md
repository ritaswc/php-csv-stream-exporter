# Csv流式导出工具

- Save Memory 节约内存
- Without File Size Limit 不限制文件长度

只能在`php-fpm模式`下使用

```shell
composer require ritaswc/csv-stream-exporter
```

```php

$exporter = new \Ritaswc\CsvStream\Exporter('自己定义导出文件名' . date('YmdHis') . '.csv');
$exporter->writeHeaders([
    '姓名',
    '电话'
]);
foreach ($lines as $line) {
    $exporter->writeLine([$line['name'], $line['phone']]);
}
$exporter = null;

```
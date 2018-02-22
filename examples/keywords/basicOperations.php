<?

/**
 * Сервис Ключевые фразы создан для удобства работы с папками, группами и ключевыми словами.
 * В данном примере производится добавление новой папки, изменение её имени,
 * новой группы и добавление туда ключевых слов.
 * https://topvisor.ru/api/v2-services/keywords_2/
 * */

use Topvisor\TopvisorSDK\V2 as TV;

// локальный путь до composer (или topvisorSDK)
include_once('/var/www/include/library/composer_libs/vendor/autoload.php');

// создание сессии
$Session = new TV\Session();

$projectId = 2121417; // id проекта

// добавление папки
$foldersAdderData = ['project_id' => $projectId,
                    'name' => 'new folder']; // генерируем данные

$foldersAdder = new TV\Pen($Session, 'add', 'keywords_2', 'folders'); // создание запроса
$foldersAdder->setData($foldersAdderData); // применение данных
$resultOfAddedFolder = $foldersAdder->exec(); // выполнение запроса

// если возникло исключение -> ошибка
if($resultOfAddedFolder->getErrors()) throw new \Exception($resultOfAddedFolder->getErrorsString());

// изменим имя папки
$foldersUpdaterData = ['project_id' => $projectId,
                        'name' => 'I can rename folder'];
$folderFilter = TV\Fields::genFilterData('id', 'EQUALS', [$folderId]);

$folderId = $resultOfAddedFolder->getResult()->id;
$foldersUpdater = new TV\Pen($Session, 'edit', 'keywords_2', 'folders/rename');
$foldersUpdater->setData($foldersUpdaterData);
$foldersUpdater->setFilters([$folderFilter]);
$foldersUpdater->exec();

// создадим группу в папке
$groupsAdderData = ['project_id' => $projectId,
                    'to_id' => $folderId];

$groupsAdder = new TV\Pen($Session, 'add', 'keywords_2', 'groups');
$groupsAdder->setData($groupsAdderData);
$resultOfAddedGroup = $groupsAdder->exec(); // Тип возвращаемого значения - array

// если возникло исключение -> ошибка
if($resultOfAddedGroup->getErrors()) throw new \Exception($resultOfAddedGroup->getErrorsString());

// добавим ключевое слово в группу
$groupId = $resultOfAddedGroup->getResult()[0]->id;
$keywordsAdderData = ['project_id' => $projectId,
                        'name' => 'new keyword',
                        'to_id' => $groupId];

$keywordsAdder = new TV\Pen($Session, 'add', 'keywords_2', 'keywords');
$keywordsAdder->setData($keywordsAdderData);
$keywordsAdder->exec();
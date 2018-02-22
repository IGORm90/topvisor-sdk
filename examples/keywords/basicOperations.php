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
$foldersAdder = new TV\Pen($Session, 'add', 'keywords_2', 'folders'); // создание запроса
$foldersAdderData = ['project_id' => $projectId, 'name' => 'new folder'];
$foldersAdder->setData($foldersAdderDataData);
$resultOfAddedFolder = $foldersAdder->exec(); // выполнение запроса

// если возникло исключение -> ошибка
if($resultOfAddedFolder->getErrors()) throw new \Exception($resultOfAddedFolder->getErrorsString());

// изменим имя папки
$folderId = $resultOfAddedFolder->getResult()->id;
$foldersUpdater = new TV\Pen($Session, 'edit', 'keywords_2', 'folders/rename');
$foldersUpdaterData = ['project_id' => $projectId, 'name' => 'I can rename folder'];
$foldersUpdater->setData($foldersUpdaterData);
$folderFilter = TV\Fields::genFilterData('id', 'EQUALS', [$folderId]);
$foldersUpdater->setFilters([$folderFilter]);
$foldersUpdater->exec();

// создадим группу в папке
$groupsAdder = new TV\Pen($Session, 'add', 'keywords_2', 'groups');
$groupsAdderData = ['project_id' => $projectId, 'to_id' => $folderId];
$groupsAdder->setData($groupsAdderData);
$resultOfAddedGroup = $groupsAdder->exec(); // Тип возвращаемого значения - array

// если возникло исключение -> ошибка
if($resultOfAddedGroup->getErrors()) throw new \Exception($resultOfAddedGroup->getErrorsString());

// добавим ключевое слово в группу
$groupId = $resultOfAddedGroup->getResult()[0]->id;
$keywordsAdder = new TV\Pen($Session, 'add', 'keywords_2', 'keywords');
$keywordsAdderData = ['project_id' => $projectId, 'name' => 'new keyword', 'to_id' => $groupId];
$keywordsAdder->setData($keywordsAdderData);
$keywordsAdder->exec();


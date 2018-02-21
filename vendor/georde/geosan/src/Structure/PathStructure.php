<?php
namespace Geosan\Structure;

class PathStructure{
	protected $pathApplication = pathApplication.'app/';
	protected $pathModules = pathApplication.'app/modules/';

	protected $pathController = pathApplication.'app/Controllers/';
	protected $pathModel = pathApplication.'app/Models/';
	protected $pathView = pathApplication.'app/Views/';

    protected $pathModuleController = 'controllers/';
	protected $pathModuleModel = 'models/';
	protected $pathModuleView = 'views/';




	protected $pathHelper = pathApplication.'functions/helpers/';
	protected $pathMigration = pathApplication.'app/migrations/';
	protected $pathCore = pathApplication.'app/core/';

	protected $pathRoute = pathApplication.'app/config/routes.php';
}
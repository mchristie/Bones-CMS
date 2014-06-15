<?php

namespace Christie\Bones;

use Illuminate\Support\ServiceProvider;

class BonesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('christie/bones');

		include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('bones', function($app) {
            return new Libraries\Bones();
        });

        $this->app->bindShared('bonesforms', function($app) {
            return new Libraries\BonesForms( $app['form'], $app['bones'] );
        });

        $this->app['command.bones'] = $this->app->share(function($app) {
            return new Commands\BonesCommand();
        });
        $this->app['command.bones.install'] = $this->app->share(function($app) {
            return new Commands\InstallBonesCommand();
        });
        $this->commands('command.bones', 'command.bones.install');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('bones', 'bonesforms');
	}

}

Bones CMS
==========

## Why Bones CMS?

Bones CMS is, as the name may suggest, a bare bones CMS. Fed up with bloated and overcomplicated CMSs, I wanted to create one which was based one an excellent framework (Laravel, of course), but wouldn't get in the way if I wanted to developer some other functionality along side it.

## What can and can't it do?

Multi-site baked into everything, as many tree or blog style content channels as you need (either global or site specific), image gallery with on-the-fly resizing, custom field types (which are very easy to create), widgets, and top-level components.

What can't it do? There's no one-click plugin installation, it's not wordpress nor is it trying to be.

### How do I get started?

Bones CMS is a composer package, but it's not yet in packagist, so add `"christie/bones": "dev-master"` to your `composer.json`, but also tell composer where to find the repo by adding this"

~~~
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/mchristie/Bones-CMS"
    }
]
~~~

Open `app/config/app.php` and add the following provider and aliases:

~~~PHP
'providers' => array(
    // ...
    'Christie\Bones\BonesServiceProvider'
),
// ...
'aliases' => array(
    // ...
    'Bones'           => 'Christie\Bones\Facades\Bones',
    'BonesForms'      => 'Christie\Bones\Facades\BonesForms',
    'Channel'         => 'Christie\Bones\Models\Channel',
    'Entry'           => 'Christie\Bones\Models\Entry'
)
~~~
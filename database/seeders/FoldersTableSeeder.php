<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use App\Models\System\Folder;

class FoldersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $folders = [
            [
                'name'      => 'inbox',
                'label'     => 'Inbox',
                'visible'   => Folder::VISIBLE,
                'lock'      => Folder::LOCKED
            ],
            [
                'name'      => 'sent',
                'label'     => 'Sent',
                'visible'   => Folder::VISIBLE,
                'lock'      => Folder::LOCKED
            ],
            [
                'name'      => 'junk',
                'label'     => 'Junk',
                'visible'   => Folder::VISIBLE,
                'lock'      => Folder::LOCKED
            ],
            [
                'name'      => 'trashed',
                'label'     => 'Trashed',
                'visible'   => Folder::VISIBLE,
                'lock'      => Folder::LOCKED
            ],
            [
                'name'      => 'archived',
                'label'     => 'Archived',
                'visible'   => Folder::VISIBLE,
                'lock'      => Folder::LOCKED
            ],
            [
                'name'      => '_hidden',
                'label'     => 'Hidden',
                'visible'   => Folder::HIDDEN,
                'lock'      => Folder::LOCKED
            ]
        ];

        foreach ($folders as $folder) {
            if (filled($folder) && array_keys_exist($folder, ['name', 'label', 'visible'])) {
                $name = $folder['name'];
                $label = $folder['label'];
                $visible = $folder['visible'];
                $lock = $folder['lock'];

                if (!Folder::where('name', $name)->exists()) {
                    $folder = new Folder;
                    $folder->name = $name;
                    $folder->label = $label;
                    $folder->visible = $visible;
                    $folder->lock = $lock;
                    $folder->saveOrFail();
                }
            }
        }
    }
}

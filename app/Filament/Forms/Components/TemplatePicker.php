<?php

namespace App\Filament\Forms\Components;

use App\Models\LaporanMasyarakat;
use Filament\Forms\Components\Field;
use Illuminate\Database\Eloquent\Model;

class TemplatePicker extends Field
{
    protected string $view = 'filament.forms.components.template-picker';

    protected $tags = [];

    public function setModel($model)
    {
        $this->tags = $model::getListOfTags();
    }
}

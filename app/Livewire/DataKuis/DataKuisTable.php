<?php

namespace App\Livewire\DataKuis;

use App\Models\Kuis;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class DataKuisTable extends PowerGridComponent
{
    public string $tableName = 'data-kuis-table-xsavcn-table';

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Kuis::query()->with(['dosen', 'kelas']);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('judul')
            ->add('deskripsi')
            ->add('id_kelas', fn($data) => $data->kelas->nama)
            ->add('id_dosen', fn($data) => $data->dosen->name)
            ->add('nilai_lulus')
            ->add('created_at_formatted', fn($data) => Carbon::parse($data->created_at)->format('d-m-Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Judul', 'judul')
                ->sortable()
                ->searchable(),

            Column::make('Deskripsi', 'deskripsi')
                ->sortable()
                ->searchable(),

            Column::make('kelas', 'id_kelas'),
            Column::make('dosen', 'id_dosen'),
            Column::make('Nilai minimum', 'nilai_lulus')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert(' . $rowId . ')');
    }

    public function actions(Kuis $row): array
    {
        return [
            Button::add('detail')
                ->slot("<i class='bx bxs-info-circle'></i>")
                ->id()
                ->class('btn btn-success text-white')
                ->route('kuis-detail', ['id' => $row->id]),
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}

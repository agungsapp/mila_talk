<?php

namespace App\Livewire\Mahasiswa;

use App\Models\Kelas;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class KelasMahasiswaTable extends PowerGridComponent
{
    public string $tableName = 'kelas-mahasiswa-table-0qzj88-table';

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
        return Kelas::query()->with(['dosen', 'mahasiswa' => function ($q) {
            $q->jumlah_siswa =  $q->count();
        }])->where('id_dosen', Auth::id());
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id', fn($row, $index) => $index + 1)
            ->add('nama')
            ->add('id_dosen', fn($data) => $data->dosen->name)
            ->add('deskripsi')
            ->add('created_at_formatted', fn($data) => Carbon::parse($data->created_at)->format('d-m-Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id'),
            Column::make('Nama', 'nama')
                ->sortable()
                ->searchable(),

            Column::make('Id dosen', 'id_dosen'),
            Column::make('Deskripsi', 'deskripsi')
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

    public function actions(Kelas $row): array
    {
        return [

            Button::add('detail')
                ->slot("<i class='bx bxs-info-circle'></i>")
                ->id()
                ->class('btn btn-success text-white')
                ->route('kelas-detail', ['id' => $row->id]),
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

@extends('layouts.app')

@section('content')



    <div class="bg-red-600 text-white px-6 py-3 rounded-t-xl">
        <h2 class="font-semibold text-lg">Recycle Bin</h2>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">
        <table class="w-full border text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase">
                    <th class="p-3">Nomor</th>
                    <th class="p-3">Judul</th>
                    <th class="p-3">Tanggal Hapus</th>
                    <th class="p-3 text-center">Preview</th>
                    <th class="p-3">Keterangan Hapus</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($documents as $doc)
                    <tr class="border-b hover:bg-gray-50">

                        <!-- NOMOR -->
                        <td class="p-3">
                            {{ $doc->document_number ?? '-' }}
                        </td>

                        <!-- JUDUL -->
                        <td class="p-3 font-semibold">
                            {{ $doc->title }}
                        </td>

                        <!-- TANGGAL HAPUS -->
                        <td class="p-3">
                            {{ $doc->deleted_at->format('d-m-Y H:i') }}
                        </td>


                        <!-- PREVIEW -->
                        <td class="p-3 text-center">
                            @if ($doc->file_document)
                                <a href="{{ route('admin.documents.stream', $doc->id) }}"
                                    class="bg-blue-500 text-white px-3 py-1 rounded text-xs">
                                    👁 Preview
                                </a>
                            @else
                                <span class="text-gray-400">Tidak ada file</span>
                            @endif
                        </td>

                        <td class="p-3 text-red-600">
                            {{ $doc->delete_reason ?? '-' }}
                        </td>

                        <!-- ACTION -->
                        <td class="p-3 flex gap-2 justify-center">

                            <form action="{{ route('admin.documents.restore', $doc->id) }}" method="POST">
                                @csrf
                                <button class="bg-green-500 text-white px-3 py-1 rounded text-xs">
                                    Restore
                                </button>
                            </form>

                            <form action="{{ route('admin.documents.forceDelete', $doc->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus permanen?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 text-white px-3 py-1 rounded text-xs">
                                    Hapus Permanen
                                </button>
                            </form>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-5 text-gray-400">
                            Recycle Bin Kosong
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection

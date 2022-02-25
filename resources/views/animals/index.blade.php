@extends('home')

@section('contents')

@if ($message = Session::get('add'))
 <div class="bg-red-500 p-4">
    <strong class="text-white text-3xl pl-4">{{ $message }}</strong>
 </div>
 @elseif ($message = Session::get('update'))
 <div class="bg-red-500 p-4">
    <strong class="text-white text-3xl pl-4">{{ $message }}</strong>
 </div>
 @elseif ($message = Session::get('delete'))
 <div class="bg-red-500 p-4">
    <strong class="text-white text-3xl pl-4">{{ $message }}</strong>
 </div>
 @elseif ($message = Session::get('restore'))
 <div class="bg-red-500 p-4">
    <strong class="text-white text-3xl pl-4">{{ $message }}</strong>
 </div>
 @elseif ($message = Session::get('force'))
 <div class="bg-red-500 p-4">
    <strong class="text-white text-3xl pl-4">{{ $message }}</strong>
 </div>
@endif

<div class="pt-8 pb-4 px-8">
    <a href="animals/create" class="p-3 border-none italic text-white bg-black text-lg">
        Add a new animal &rarr;
    </a>
</div>

<div class="py-3">
    <table class="table-auto">
        <tr class="text-white">
            <th class="w-screen text-3xl">Id</th> 
            <th class="w-screen text-3xl">Animal Name</th>
            <th class="w-screen text-3xl">Age</th>
            <th class="w-screen text-3xl">Gender</th>
            <th class="w-screen text-3xl">Type of Animal</th>
            <th class="w-screen text-3xl">Rescuer</th>
            <th class="w-screen text-3xl">Animal Pic</th>
            <th class="w-screen text-3xl">Update</th>
            <th class="w-screen text-3xl">Delete</th>
            <th class="w-screen text-3xl">Restore</th>
            <th class="w-screen text-3xl">Destroy</th>
        </tr>

  @forelse ($animals as $animal)
      <tr>
        <td class=" text-center text-3xl">
            {{ $animal->animals_id }}
      </td>
          <td class=" text-center text-3xl">
                {{ $animal->animal_name }}
          </td>
          <td class=" text-center text-3xl">
                {{ $animal->age }}
          </td>
          <td class=" text-center text-3xl">
                {{ $animal->gender }}
          </td>
          <td class=" text-center text-3xl">
                {{ $animal->type }}
          </td>
          <td class=" text-center text-3xl">
            {{ $animal->rescuer->last_name }},{{ $animal->rescuer->first_name }}
         </td>
          <td class="pl-10">
            <img src="{{ asset('uploads/animals/'.$animal->images)}}" alt="I am A Pic" width="75" height="75">
          </td>
          <td class=" text-center">
            <a href="animals/{{ $animal->animals_id }}/edit" class="text-center text-3xl bg-green-600 p-2">
                Update &rarr; 
               </a>
          </td>
          <td class=" text-center">
            <form action="/animals/{{ $animal->animals_id }}" method="POST">
                @csrf
                @method('delete')
                <button type="submit" class="text-center text-3xl bg-red-600 p-2">
                    Delete &rarr;
                </button>
           </form>
          </td>
      </tr>
            @empty
                <p>No Animals Data in the Database</p>
            @endforelse
        </table>
    </div>
</div>
@endsection
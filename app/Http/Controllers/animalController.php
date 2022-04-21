<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Http\Requests\animalRequest;
use RealRashid\SweetAlert\Facades\Alert;

class animalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $animals = Animal::join(
            "customers",
            "customers.id",
            "=",
            "animals.customer_id"
        )
            ->select(
                "customers.first_name",
                "animals.id",
                "animals.animal_name",
                "animals.age",
                "animals.gender",
                "animals.type",
                "animals.images",
                "animals.customer_id",
                "animals.deleted_at"
            )
            ->orderBy("animals.id", "ASC")
            ->withTrashed()
            ->paginate(6);

        if (session(key: "success_message")) {
            Alert::image(
                "Congratulations!",
                session(key: "success_message"),
                "https://freight.cargo.site/w/367/i/658c2312424eedf51104e7dbaa820d5f5e5c0e0e63422fbffd2378026cc5bcd3/doge.gif",
                "200",
                "200",
                "I Am A Pic"
            );
        }

        return view("animals.index", ["animals" => $animals]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::pluck("first_name", "id");
        return view("animals.create", [
            "customers" => $customers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(animalRequest $request)
    {
        $animals = new Animal();
        $animals->animal_name = $request->input("animal_name");
        $animals->age = $request->input("age");
        $animals->gender = $request->input("gender");
        $animals->type = $request->input("type");
        $animals->customer_id = $request->input("customer_id");
        if ($request->hasfile("images")) {
            $file = $request->file("images");
            $filename = uniqid() . "_" . $file->getClientOriginalName();
            $file->move("uploads/animals/", $filename);
            $animals->images = $filename;
        }
        $animals->save();
        return Redirect::to("/animals")->withSuccessMessage(
            "New Animal Added!"
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $animals = Customer::leftJoin(
            "animals",
            "animals.id",
            "=",
            "animals.customer_id"
        )
            ->select(
                "animals.id",
                "animals.animal_name",
                "animals.age",
                "animals.gender",
                "animals.type",
                "animals.images",
                "animals.deleted_at",
                "animals.animal_name"
            )
            ->where('animals.id', $id)
            ->get();

        return View::make('animals.show', compact('animals'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $animals = Animal::find($id);
        $customers = Customer::pluck("first_name", "id");
        return view("animals.edit", [
            "animals" => $animals,
            "customers" => $customers,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(animalRequest $request, $id)
    {
        $animals = Animal::find($id);
        $animals->animal_name = $request->input("animal_name");
        $animals->age = $request->input("age");
        $animals->gender = $request->input("gender");
        $animals->type = $request->input("type");
        $animals->customer_id = $request->input("customer_id");
        if ($request->hasfile("images")) {
            $destination = "uploads/animals/" . $animals->images;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file("images");
            $filename = uniqid() . "_" . $file->getClientOriginalName();
            $file->move("uploads/animals/", $filename);
            $animals->images = $filename;
        }
        $animals->update();
        return Redirect::to("/animals")->withSuccessMessage(
            "Animal Data Updated!"
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Animal::destroy($id);
        return Redirect::to("/animals")->withSuccessMessage(
            "Animal Data Deleted!"
        );
    }

    public function restore($id)
    {
        Animal::onlyTrashed()
            ->findOrFail($id)
            ->restore();
        return Redirect::route("animals.index")->withSuccessMessage(
            "Animal Data Restored!"
        );
    }

    public function forceDelete($id)
    {
        $animals = Animal::findOrFail($id);
        $destination = "uploads/animals/" . $animals->images;
        if (File::exists($destination)) {
            File::delete($destination);
        }
        $animals->forceDelete();
        return Redirect::route("animals.index")->withSuccessMessage(
            "Animal Data Permanently Deleted!"
        );
    }
}

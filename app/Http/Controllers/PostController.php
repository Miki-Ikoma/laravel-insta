<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category; // for display
use Illuminate\Support\Facades\Auth; // use loggedin user_id

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    public function create()
    {
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    public function store(Request $request)
    {
        #1. Validate all form data
        $request->validate([
            'category'    => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image'       => 'required|mimes:jpeg,jpg,png,gif|max:1048'
        ]);

        #2. Save the post
        $this->post->user_id = Auth::user()->id;
        $this->post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->description = $request->description;
        $this->post->save(); // same to INSERT INTO ...

        #3. Save the categories to the category_post table
        foreach ($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
        }
        $this->post->categoryPost()->createMany($category_post);

        #4. Go back to homepage
        return redirect()->route('index');
    }

    public function show($id)
    {  
        $post = $this->post->findOrFail($id);

        return view('users.posts.show')
                ->with('post', $post);
    }

    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        # If the auth user is not the owner of the post, redirect to homepage.
        if(Auth::user()->id != $post->user->id){
            return redirect()->route('index');
        }

        $all_categories = $this->category->all();

        # Get all the category IDs of this post, save in an array.
        $selected_categories = []; // just error handling, if I remove this, I get an error when I remove the category.
        foreach($post->categoryPost as $category_post){
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit')
                ->with('post', $post)
                ->with('all_categories', $all_categories)
                ->with('selected_categories', $selected_categories);
    }

    public function update(Request $request, $id)
    {
        #1. Validate the data from the form
        $request->validate([
            'category'      =>      'required|array|between:1,3',
                // My error - forgetting :, like between:1,3
            'description'   =>      'required|min:1|max:1000',
            'image'         =>      'mimes:jpg,jpeg,png,gif|max:1048'
        ]);

        #2. Update the post
        $post               =   $this->post->findOrFail($id);
        $post->description  =   $request->description;

        //If there is a new image
        if($post->image){
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
                # My error: forgetting ; before base64 like ';base64,'. It's important!!
                # I can change my encode url inside phpmyadmin. Duble clicking!
        }

        $post->save();

        #3. Delete all records from category_post related to this post
        $post->categoryPost()->delete();
        //Use the relationship post::categoryPost() to select the records related to a post
        //Equivalent: DELETE FROM category_post where post_id = $id;

        #4. Save the new categories to category_post table
        foreach($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
        }
        $post->categoryPost()->createMany($category_post);

        return redirect()->route('post.show', $id);
    }

    public function destroy($id)
    {
        // Create PostController::destroy()
        // Delete/Destroy the post
        // Redirect back to homepage
        // Create the route
        // Use the route in the delete modal.


        // $post = $this->post->findOrFail($id);
        # delete image - No need.

        # delete post
        // $post->delete();

        $this->post->findOrFail($id)->forceDelete();
        return redirect()->route('index');

    }

}

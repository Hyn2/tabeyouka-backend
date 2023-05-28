public function edit(Community $community)
{
    return view('community.edit', ['post' => $community]);
}

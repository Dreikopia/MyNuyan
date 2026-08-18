  @props(['categories'])

  <x-modal id="CreateCategory" name="New Article" boxClass="2xl" class="btn btn-primary">
      <form method="POST" action="{{ route('admin.news.store') }}">
          @csrf
          <div class="flex flex-col">
              <div class="space-y-4">
                  <x-field name="title" label="Title" placeholder="News Title" />

                  <div class="flex justify-between gap-4">

                      <x-field name="category" label="Category" type="select">
                          @foreach ($categories as $category)
                              <option value="{{ $category->id }}" @selected(old('category') == $category->id)>
                                  {{ $category->name }}
                              </option>
                          @endforeach
                      </x-field>


                      <x-field name="status" label="Status" type="select">
                          @foreach (App\Enums\NewsStatus::cases() as $status)
                              <option value="{{ $status->value }}" @selected(old('status') === $status->value)>
                                  {{ ucfirst($status->value) }}
                              </option>
                          @endforeach
                      </x-field>
                  </div>

                  <x-field name="description" type="textarea" label="Content"
                      placeholder="Write the article content here" />

                  <div class="flex gap-2 mt-4">


                      <button type="button" class="btn btn-outline flex-1"
                          onclick="document.getElementById('CreateCategory').close()">
                          Cancel
                      </button>

                      <button type="submit" class="btn btn-primary flex-1">
                          Publish
                      </button>
                  </div>
              </div>
      </form>
  </x-modal>

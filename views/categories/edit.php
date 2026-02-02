<?php $pageTitle = 'Edit Category'; ?>

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Edit Category</h2>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="/categories/update/<?= $category['category_id'] ?>">
        <?= Security::csrfField() ?>

        <div class="mb-4">
            <label for="category_name" class="block text-sm font-medium text-gray-700 mb-2">
                Category Name <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="category_name"
                   name="category_name"
                   value="<?= Security::e(Session::getFlash('old_input')['category_name'] ?? $category['category_name']) ?>"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="category_code" class="block text-sm font-medium text-gray-700 mb-2">
                Category Code <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="category_code"
                   name="category_code"
                   value="<?= Security::e(Session::getFlash('old_input')['category_code'] ?? $category['category_code']) ?>"
                   required
                   maxlength="20"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-4">
            <label for="parent_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                Parent Category
            </label>
            <select id="parent_category_id"
                    name="parent_category_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- None (Root Category) --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>"
                            <?= ($category['parent_category_id'] ?? '') == $cat['category_id'] ? 'selected' : '' ?>>
                        <?= Security::e($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox"
                       name="is_fuel_category"
                       value="1"
                       <?= ($category['is_fuel_category'] ?? false) ? 'checked' : '' ?>
                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">This is a fuel category</span>
            </label>
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Description
            </label>
            <textarea id="description"
                      name="description"
                      rows="3"
                      maxlength="500"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?= Security::e($category['description'] ?? '') ?></textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="/categories" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Update Category
            </button>
        </div>
    </form>
</div>

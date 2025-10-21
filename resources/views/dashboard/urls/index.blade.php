@extends('layouts.app')

@section('title', 'URL Management - Short URL Manager')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-100">URL Management</h1>
        <p class="text-gray-400 mt-2">Create and manage your short URLs and link rotators</p>
    </div>

    <!-- Create New URL Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Create Short URL -->
        <div class="glass-effect rounded-xl p-6">
            <h2 class="text-xl font-semibold text-gray-100 mb-4">Create Short URL</h2>
            <form id="shortUrlForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Original URL</label>
                    <input type="url" name="url" required 
                           class="w-full px-4 py-2 bg-dark-800 border border-dark-600 rounded-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="https://example.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Title (Optional)</label>
                    <input type="text" name="title" 
                           class="w-full px-4 py-2 bg-dark-800 border border-dark-600 rounded-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="My Link">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Custom Code (Optional)</label>
                    <input type="text" name="custom_code" maxlength="10"
                           class="w-full px-4 py-2 bg-dark-800 border border-dark-600 rounded-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="mylink">
                </div>
                <button type="submit" class="w-full btn-primary text-white font-semibold py-2 px-4 rounded-lg">
                    Create Short URL
                </button>
            </form>
        </div>

        <!-- Create Rotator -->
        <div class="glass-effect rounded-xl p-6">
            <h2 class="text-xl font-semibold text-gray-100 mb-4">Create Link Rotator</h2>
            <form id="rotatorForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Rotator Name</label>
                    <input type="text" name="name" required 
                           class="w-full px-4 py-2 bg-dark-800 border border-dark-600 rounded-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="My Rotator">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Rotation Type</label>
                    <select name="rotation_type" required 
                            class="w-full px-4 py-2 bg-dark-800 border border-dark-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="random">Random</option>
                        <option value="sequential">Sequential</option>
                        <option value="weighted">Weighted</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Custom Code (Optional)</label>
                    <input type="text" name="custom_code" maxlength="10"
                           class="w-full px-4 py-2 bg-dark-800 border border-dark-600 rounded-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           placeholder="rotator">
                </div>
                <div id="urlsContainer">
                    <label class="block text-sm font-medium text-gray-300 mb-2">URLs</label>
                    <div class="url-input-group mb-2">
                        <input type="url" name="urls[0][url]" required 
                               class="w-full px-4 py-2 bg-dark-800 border border-dark-600 rounded-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="https://example1.com">
                    </div>
                    <div class="url-input-group mb-2">
                        <input type="url" name="urls[1][url]" required 
                               class="w-full px-4 py-2 bg-dark-800 border border-dark-600 rounded-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="https://example2.com">
                    </div>
                </div>
                <button type="button" id="addUrlBtn" class="text-blue-400 hover:text-blue-300 text-sm">+ Add Another URL</button>
                <button type="submit" class="w-full btn-primary text-white font-semibold py-2 px-4 rounded-lg">
                    Create Rotator
                </button>
            </form>
        </div>
    </div>

    <!-- Short URLs Table -->
    <div class="glass-effect rounded-xl p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-100 mb-4">Short URLs</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-dark-700">
                        <th class="pb-3 text-gray-300">Short Code</th>
                        <th class="pb-3 text-gray-300">Original URL</th>
                        <th class="pb-3 text-gray-300">Title</th>
                        <th class="pb-3 text-gray-300">Clicks</th>
                        <th class="pb-3 text-gray-300">Status</th>
                        <th class="pb-3 text-gray-300">Created</th>
                        <th class="pb-3 text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shortUrls as $shortUrl)
                    <tr class="border-b border-dark-800">
                        <td class="py-3">
                            <code class="bg-dark-800 px-2 py-1 rounded text-blue-400">{{ $shortUrl->short_code }}</code>
                        </td>
                        <td class="py-3 text-gray-300 max-w-xs truncate">{{ $shortUrl->original_url }}</td>
                        <td class="py-3 text-gray-300">{{ $shortUrl->title ?: '-' }}</td>
                        <td class="py-3 text-green-400 font-medium">{{ number_format($shortUrl->clicks_count) }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $shortUrl->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $shortUrl->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-400 text-sm">{{ $shortUrl->created_at->format('M j, Y') }}</td>
                        <td class="py-3">
                            <button onclick="toggleStatus('short_url', {{ $shortUrl->id }}, {{ $shortUrl->is_active ? 'false' : 'true' }})"
                                    class="text-blue-400 hover:text-blue-300 text-sm">
                                {{ $shortUrl->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $shortUrls->links() }}</div>
    </div>

    <!-- Rotator Groups Table -->
    <div class="glass-effect rounded-xl p-6">
        <h2 class="text-xl font-semibold text-gray-100 mb-4">Link Rotators</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-dark-700">
                        <th class="pb-3 text-gray-300">Short Code</th>
                        <th class="pb-3 text-gray-300">Name</th>
                        <th class="pb-3 text-gray-300">Type</th>
                        <th class="pb-3 text-gray-300">Clicks</th>
                        <th class="pb-3 text-gray-300">Status</th>
                        <th class="pb-3 text-gray-300">Created</th>
                        <th class="pb-3 text-gray-300">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rotatorGroups as $rotator)
                    <tr class="border-b border-dark-800">
                        <td class="py-3">
                            <code class="bg-dark-800 px-2 py-1 rounded text-purple-400">{{ $rotator->short_code }}</code>
                        </td>
                        <td class="py-3 text-gray-300">{{ $rotator->name }}</td>
                        <td class="py-3 text-gray-300 capitalize">{{ $rotator->rotation_type }}</td>
                        <td class="py-3 text-green-400 font-medium">{{ number_format($rotator->clicks_count) }}</td>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $rotator->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $rotator->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3 text-gray-400 text-sm">{{ $rotator->created_at->format('M j, Y') }}</td>
                        <td class="py-3">
                            <button onclick="toggleStatus('rotator', {{ $rotator->id }}, {{ $rotator->is_active ? 'false' : 'true' }})"
                                    class="text-blue-400 hover:text-blue-300 text-sm">
                                {{ $rotator->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $rotatorGroups->links() }}</div>
    </div>
</div>

@push('scripts')
<script>
    let urlCount = 2;

    // Add URL to rotator form
    document.getElementById('addUrlBtn').addEventListener('click', function() {
        const container = document.getElementById('urlsContainer');
        const newUrlGroup = document.createElement('div');
        newUrlGroup.className = 'url-input-group mb-2';
        newUrlGroup.innerHTML = `
            <div class="flex gap-2">
                <input type="url" name="urls[${urlCount}][url]" required 
                       class="flex-1 px-4 py-2 bg-dark-800 border border-dark-600 rounded-lg text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       placeholder="https://example.com">
                <button type="button" onclick="this.parentElement.parentElement.remove()" 
                        class="px-3 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30">Remove</button>
            </div>
        `;
        container.appendChild(newUrlGroup);
        urlCount++;
    });

    // Short URL form submission
    document.getElementById('shortUrlForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch('{{ route("urls.create.short") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert(`Short URL created: ${result.short_url}`);
                this.reset();
                location.reload();
            } else {
                alert('Error creating short URL');
            }
        } catch (error) {
            alert('Error creating short URL');
        }
    });

    // Rotator form submission
    document.getElementById('rotatorForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch('{{ route("urls.create.rotator") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert('Rotator created successfully!');
                this.reset();
                location.reload();
            } else {
                alert('Error creating rotator');
            }
        } catch (error) {
            alert('Error creating rotator');
        }
    });

    // Toggle status function
    async function toggleStatus(type, id, newStatus) {
        try {
            const response = await fetch(`/urls/${type}/${id}/toggle`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                location.reload();
            } else {
                alert('Error updating status');
            }
        } catch (error) {
            alert('Error updating status');
        }
    }
</script>
@endpush
@endsection
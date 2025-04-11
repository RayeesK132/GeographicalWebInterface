import React, { useState } from 'react';

const AssetForm = ({ onSubmit, initialData = null }) => {
    const [asset, setAsset] = useState(initialData || {
        name: '',
        description: '',
        category: '',
        department: ''
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        onSubmit(asset);
    };

    return (
        <form onSubmit={handleSubmit} className="asset-form">
            <input
                type="text"
                value={asset.name}
                onChange={(e) => setAsset({...asset, name: e.target.value})}
                placeholder="Asset Name"
                required
            />
            <textarea
                value={asset.description}
                onChange={(e) => setAsset({...asset, description: e.target.value})}
                placeholder="Description"
                required
            />
            <input
                type="text"
                value={asset.category}
                onChange={(e) => setAsset({...asset, category: e.target.value})}
                placeholder="Category"
                required
            />
            <input
                type="text"
                value={asset.department}
                onChange={(e) => setAsset({...asset, department: e.target.value})}
                placeholder="Department"
                required
            />
            <button type="submit">
                {initialData ? 'Update Asset' : 'Create Asset'}
            </button>
        </form>
    );
};

export default AssetForm;

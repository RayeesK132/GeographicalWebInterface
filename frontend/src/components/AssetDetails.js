import React, { useState, useEffect } from 'react';

const AssetDetails = ({ assetId, onClose }) => {
    const [asset, setAsset] = useState(null);
    const [loading, setLoading] = useState(true);
    const [editing, setEditing] = useState(false);
    const [formData, setFormData] = useState({});

    useEffect(() => {
        fetchAssetDetails();
    }, [assetId]);

    const fetchAssetDetails = async () => {
        try {
            const response = await fetch(`http://localhost/backend/api/assets/${assetId}`);
            const data = await response.json();
            setAsset(data);
            setFormData(data);
        } catch (error) {
            console.error('Error fetching asset details:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleUpdate = async (e) => {
        e.preventDefault();
        try {
            const response = await fetch(`http://localhost/backend/api/assets/${assetId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
            if (response.ok) {
                setAsset(formData);
                setEditing(false);
            }
        } catch (error) {
            console.error('Error updating asset:', error);
        }
    };

    return (
        <div className="asset-details">
            {loading ? (
                <div>Loading...</div>
            ) : (
                <>
                    {editing ? (
                        <form onSubmit={handleUpdate}>
                            <input
                                type="text"
                                value={formData.name}
                                onChange={(e) => setFormData({...formData, name: e.target.value})}
                            />
                            <textarea
                                value={formData.description}
                                onChange={(e) => setFormData({...formData, description: e.target.value})}
                            />
                            <button type="submit">Save</button>
                            <button type="button" onClick={() => setEditing(false)}>Cancel</button>
                        </form>
                    ) : (
                        <div>
                            <h3>{asset.name}</h3>
                            <p>{asset.description}</p>
                            <p>Location: {asset.latitude}, {asset.longitude}</p>
                            <p>Category: {asset.category}</p>
                            <p>Department: {asset.department}</p>
                            <button onClick={() => setEditing(true)}>Edit</button>
                            <button onClick={onClose}>Close</button>
                        </div>
                    )}
                </>
            )}
        </div>
    );
};

export default AssetDetails;

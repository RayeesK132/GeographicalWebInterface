import React from 'react';
import { useAuth } from '../context/AuthContext';

const MapControls = ({ map, filters, setFilters }) => {
    const { user } = useAuth();
    const permissions = user?.permissions || {};

    return (
        <div className="map-controls">
            <div className="zoom-controls">
                <button onClick={() => map.zoomIn()}>+</button>
                <button onClick={() => map.zoomOut()}>-</button>
            </div>
            {permissions.useFilters && (
                <div className="view-controls">
                    <button onClick={() => map.setView([53.795, -1.759], 13)}>Reset View</button>
                    <select onChange={(e) => map.setStyle(e.target.value)}>
                        <option value="streets">Streets</option>
                        <option value="satellite">Satellite</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
            )}
            {permissions.useAdvancedFilters && (
                <div className="advanced-filters">
                    <input
                        type="date"
                        onChange={(e) => setFilters({...filters, date: e.target.value})}
                    />
                    <input
                        type="range"
                        min="0"
                        max="100"
                        onChange={(e) => setFilters({...filters, radius: e.target.value})}
                    />
                </div>
            )}
        </div>
    );
};

export default MapControls;

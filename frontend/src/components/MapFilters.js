import React from 'react';

const MapFilters = ({ filters, setFilters, categories, departments }) => {
    return (
        <div className="map-filters">
            <select
                value={filters.category}
                onChange={(e) => setFilters({...filters, category: e.target.value})}
            >
                <option value="all">All Categories</option>
                {categories.map(cat => (
                    <option key={cat} value={cat}>{cat}</option>
                ))}
            </select>
            <select
                value={filters.department}
                onChange={(e) => setFilters({...filters, department: e.target.value})}
            >
                <option value="all">All Departments</option>
                {departments.map(dept => (
                    <option key={dept} value={dept}>{dept}</option>
                ))}
            </select>
        </div>
    );
};

export default MapFilters;

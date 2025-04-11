import React, { useState, useEffect } from 'react';

const Notification = ({ message, type = 'info', duration = 3000, onClose }) => {
    const [visible, setVisible] = useState(true);

    useEffect(() => {
        if (message) {
            setVisible(true);
            const timer = setTimeout(() => {
                handleClose();
            }, duration);
            return () => clearTimeout(timer);
        }
    }, [message, duration]);

    const handleClose = () => {
        setVisible(false);
        if (onClose) {
            onClose();
        }
    };

    if (!visible || !message) {
        return null;
    }

    return (
        <div className="notification" data-testid="notification">
            {message}
            <button onClick={handleClose} data-testid="close-button">×</button>
        </div>
    );
};

export default Notification;

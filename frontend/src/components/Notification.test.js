import React from 'react';
import { render, screen, fireEvent, act } from '@testing-library/react';
import { createRoot } from 'react-dom/client';
import Notification from './Notification';

let container = null;
let root = null;

beforeEach(() => {
    container = document.createElement("div");
    document.body.appendChild(container);
    root = createRoot(container);
});

afterEach(() => {
    act(() => {
        root.unmount();
    });
    container.remove();
    container = null;
    root = null;
});

it("calls onClose when the close button is clicked", async () => {
    const onClose = jest.fn();
    
    await act(async () => {
        root.render(<Notification message="Hello" onClose={onClose} />);
    });

    const closeButton = screen.getByTestId("close-button");
    
    await act(async () => {
        fireEvent.click(closeButton);
    });

    expect(onClose).toHaveBeenCalledTimes(1);
});

it("renders with a message", async () => {
    await act(async () => {
        root.render(<Notification message="Hello" />);
    });
    expect(screen.getByTestId("notification")).toHaveTextContent("Hello");
});

it("renders without a message", async () => {
    await act(async () => {
        root.render(<Notification message={null} />);
    });
    expect(container.textContent).toBe("");
});

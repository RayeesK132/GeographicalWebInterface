import '@testing-library/jest-dom';
import { server } from './mocks/server';

global.beforeAll(() => {
  server.listen({ onUnhandledRequest: 'error' });
});

global.afterEach(() => {
  server.resetHandlers();
});

global.afterAll(() => {
  server.close();
});

// Configure testing environment
window.matchMedia = window.matchMedia || function() {
  return {
    matches: false,
    addListener: function() {},
    removeListener: function() {}
  };
};

// Mock localStorage
const localStorageMock = {
    getItem: jest.fn(),
    setItem: jest.fn(),
    removeItem: jest.fn(),
    clear: jest.fn(),
};
global.localStorage = localStorageMock;

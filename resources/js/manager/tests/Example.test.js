import React from "react";
import { render, unmountComponentAtNode } from "react-dom";
import { act } from "react-dom/test-utils";
import { MemoryRouter, Route } from "react-router-dom";

import Hello from "../ExampleTest";

let container = null;

beforeEach(() => {
    // setup a DOM element as a render target
    container = document.createElement("div");
    document.body.appendChild(container);
});

afterEach(() => {
    // cleanup on exiting
    unmountComponentAtNode(container);
    container.remove();
    container = null;
});

it("renders with or without a name", () => {
    act(() => {
        render(
            <MemoryRouter initialEntries={['/manager/users/1']} initialIndex={1}>
                <Route path="/manager/users/:userId">
                    <Hello />
                </Route>
            </MemoryRouter>, 
            container
        );
    });
    expect(container.textContent).toBe("Hey, stranger > the user ID was got and the value is: 1");
  
    act(() => {
        render(
            <MemoryRouter>
                <Hello name="Jenny" />
            </MemoryRouter>,
            container
        );
    });
    expect(container.textContent).toBe("Hello, Jenny!");
  
    act(() => {
        render(
            <MemoryRouter>
                <Hello name="Margaret" />
            </MemoryRouter>,
            container
        );
    });
    expect(container.textContent).toBe("Hello, Margaret!");
});
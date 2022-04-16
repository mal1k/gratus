import React from "react";
import { render, unmountComponentAtNode } from "react-dom";
import { act } from "react-dom/test-utils";
import { MemoryRouter, Route } from "react-router-dom";
import renderer from 'react-test-renderer'; 
import pretty from "pretty";

import Form from "../components/Form";
import UserFormFields from "../pages/users/FormFields";
import FontAwesomeRegister from "../fontawesome";

FontAwesomeRegister();

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

it("The user form is loading", () => {
    // The test without componentDidMount
    const component = renderer.create(
        <MemoryRouter>
            <Form
                model="users" 
                url="create"
                fields={ UserFormFields() }
                currentText="Create an user" />
        </MemoryRouter>,
    );
    let tree = component.toJSON();
    expect(tree).toMatchSnapshot();
});

it("The user creation form was rendered", () => {
    act(() => {
        render(
            <MemoryRouter initialEntries={['/manager/users/create']} initialIndex={ 1 }>
                <Route path="/manager/users/create">
                    <Form
                        model="users" 
                        url="create"
                        fields={ UserFormFields() }
                        currentText="Create an user" />
                </Route>
            </MemoryRouter>, 
            container
        );
    });
    expect(pretty(container.innerHTML)).toMatchSnapshot();
});
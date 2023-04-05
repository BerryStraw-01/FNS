import {Route, Routes} from "react-router-dom";
import {ServiceList} from "./service/serviceList";
import React from "react";
import {Service} from "./service/service";
import {ServicePage} from "./service/servicePage";

export function Contents() {
    return <Routes>
        <Route path={Pages.serviceList} element={<ServiceList/>}/>
        <Route path={Pages.service} element={<ServicePage/>}/>
        <Route path={"/*"} element={<Service strId="top"/>}/>
    </Routes>
}

export const Pages = {
    top: "/",
    serviceList: "/app/service-list",
    service: "/app/service/:id",
} as const;

import React from "react";
import ApiService from "../Services/ApiService";
import withContainer from "../HOCs/withContainer";
import MaterialTable from "material-table";
import Timeline from "react-visjs-timeline";


import Grid from "@material-ui/core/Grid";
import Paper from "@material-ui/core/Paper";
import * as _ from 'lodash';
import moment from "moment";
import Button from "@material-ui/core/Button";
import {Typography} from "@material-ui/core";


const Home = props => {
    const [tasks, setTasks] = React.useState([]);
    const [groups, setGroups] = React.useState([]);
    const [events, setEvents] = React.useState([]);
    const [devs, setDevs] = React.useState([]);
    const columns = [
        {title: 'ID', field: 'id'},
        {title: 'Task identifier', field: 'identifier'},
        {title: 'Task Estimation (hours)', field: 'estimation'},
        {title: 'Task Complexity', field: 'complexity'},
        {title: 'Provider', field: 'provider'},
    ];

    React.useEffect(() => {
        getTaskList();
    }, [])

    const getTaskList = async () => {
        props.setLoading(true);
        const response = await ApiService.task.list();
        if (response.ok) {
            setTasks(response.data)
        }
        props.setLoading(false);
    }

    const getDistributedList = async (approach) => {
        props.setLoading(true);
        let response;
        if (approach === 1) {
            response = await ApiService.task.distribute();
        } else {
            response = await ApiService.task.distribute2();
        }
        const {data, ok} = response;

        if (ok) {
            const groups = _.map(data.devs, (dev) => {
                return {
                    id: dev.name,
                    content: dev.name
                };
            });
            setGroups(groups)

            const events = _.map(data.tasks, (event, i) => {
                return {
                    start: moment(event.start).subtract(3, "hours"),
                    end: moment(event.end).subtract(3, "hours"),
                    content: `${event.task} - ${event.complexity}`,
                    id: `${event.task}-${i}-${moment(event.start).toISOString()}`,
                    group: event.dev,
                    type: 'background',
                    style: `background-color: ${str2Hex(event.task + moment(event.start).toISOString())}; color: white`,
                    data: event
                };
            })
            setEvents(events)
        }
        setDevs(data.devs)
        props.setLoading(false);
    }

    const str2Hex = str => {
        let i;
        let hash = 0;
        for (i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        let colour = '#';
        for (i = 0; i < 3; i++) {
            const value = (hash >> (i * 8)) & 0xFF;
            colour += ('00' + value.toString(16)).substr(-2);
        }
        return colour;
    }

    return (
        <Grid container spacing={2} component={Paper}>
            <Grid item xs={12}>
                <Button variant="contained" color={"primary"} onClick={() => {
                    getDistributedList(1)
                }}>Distribute tasks Approach 1</Button>
                <Button variant="contained" color={"primary"} onClick={() => {
                    getDistributedList(2)
                }}>Distribute tasks Approach 2</Button>
                {devs.map(dev => (<Typography><b>{dev.name}</b> - Total days: {dev.totalDay}</Typography>))}
            </Grid>
            <Grid item xs={12}>
                <Timeline
                    items={events}
                    groups={groups}
                    defaultTimeStart={moment().add(-24, 'hour')}
                    defaultTimeEnd={moment().add(24, 'hour')}
                    traditionalZoom={true}
                />
            </Grid>
            <Grid item xs={12}>
                <MaterialTable columns={columns} data={tasks} options={{pageSize: 10}} title={'Task list'}/>
            </Grid>
        </Grid>
    )
}

export default withContainer(Home);
<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <form @submit.prevent>
          <div class="form-group">
            <label for="event_name">Nombre del Día No Laboral</label>
            <input type="text" id="event_name" class="form-control" v-model="newEvent.event_name">
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="start_date">Fecha de Inicio</label>
                <input
                  type="date"
                  id="start_date"
                  class="form-control"
                  v-model="newEvent.start_date"
                >
              </div>
               
            </div>
           <!-- <div class="col-md-6">
              Ingrese la hora del evento (*)
                                     <div class="form-group">
                                        <input type="time" class="form-control" v-model="newEvent.start_time" >
                                    </div>
                                     </div> -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="end_date">Fecha de Finalización</label>
                <input type="date" id="end_date" class="form-control" v-model="newEvent.end_date">
              </div>
            </div>
             <!-- <div class="col-md-6">
              Ingrese la hora en que finaliza evento (*)
                                     <div class="form-group">
                                        <input type="time" class="form-control" v-model="newEvent.end_time" >
                                    </div>
                                     </div> -->
            <div class="col-md-6 mb-4" v-if="calendarOptions.addingMode">
              <button class="btn btn-sm btn-primary" @click="addNewEvent">Agregar Día No Laboral</button>
            </div>
            <template v-else>
              <div class="col-md-6 mb-4">
                <button class="btn btn-sm btn-success" @click="updateEvent">Actualizar</button>
                <button class="btn btn-sm btn-danger" @click="deleteEvent">Eliminar</button>
                <button class="btn btn-sm btn-secondary" @click="calendarOptions.addingMode = !calendarOptions.addingMode">Cancelar</button>
              </div>
            </template>
            <div v-show="errorComponente" class="form-group row div-error">
                <div class="text-center text-error">
                  <div
                    v-for="error in errorMostrarMsjForm"
                    :key="error"
                    v-text="error"
                  ></div>
                </div>
              </div>
          </div>
        </form>
      </div>
      <div class="col-md-8" >
        <Fullcalendar  :options="calendarOptions" />
      </div>
    </div>
  </div>
</template>

<script>
import Fullcalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
  components: {
    Fullcalendar
  },
  data() {
    return {
      errorComponente: 0,
      errorMostrarMsjForm: [],
       calendarOptions: {
       plugins: [dayGridPlugin, interactionPlugin],
        events: "",
        locale: "es",
       eventClick: this.showEvent,
        addingMode: true
     
      },
     
      newEvent: {
        event_name: "",
        start_date: "",
        start_time: "",
        end_time: "",
        end_date: ""
      },
      
       indexToUpdate: ""
    };
  },
  created() {
    this.getEvents();
  },
  methods: {
    addNewEvent() {
       if (this.validarForm()) {
        return;
      }
      axios
        .post("/api/calendar", {
          ...this.newEvent
        })
        .then(data => {
          this.getEvents(); // update our list of events
          this.resetForm(); // clear newEvent properties (e.g. title, start_date and end_date)
        })
        .catch(err =>
          console.log("Unable to add new event!", err.response.data)
        );
        
    },
    handleDateClick: function(arg) {
      alert('date click! ' + arg.dateStr);
       console.log("show");
      this.calendarOptions.addingMode = false;
      const { id, title, start, end } = this.calendarOptions.events.find(
        event => event.id === +arg.event.id
      );
      this.indexToUpdate = id;
      this.newEvent = {
        event_name: title,
        start_date: start,
        end_date: end
      };
    },
    showEvent(arg) {
      console.log("show");
      this.calendarOptions.addingMode = false;
      const { id, title, start, end } = this.calendarOptions.events.find(
        event => event.id === +arg.event.id
      );
      this.indexToUpdate = id;
      this.newEvent = {
        id: id,
        event_name: title,
       /*  start_date: start.slice(0,10),
        end_date: end.slice(0,10),
        end_time: end.slice(-8),
        start_time: start.slice(-8), */
         start_date: start,
        end_date: end
       
      };
    },
    updateEvent() {
       if (this.validarForm()) {
        return;
      }
      axios
        .put("/api/calendar/" + this.indexToUpdate, {
          ...this.newEvent
        })
        .then(resp => {
          this.resetForm();
          this.getEvents();
          this.calendarOptions.addingMode = !this.calendarOptions.addingMode;
        })
        .catch(err =>
          console.log("Unable to update event!", err.response.data)
        );
    },
    deleteEvent() {
      axios
        .delete("/api/calendar/" + this.indexToUpdate)
        .then(resp => {
          this.resetForm();
          this.getEvents();
          this.calendarOptions.addingMode = !this.calendarOptions.addingMode;
        })
        .catch(err =>
          console.log("Unable to delete event!", err.response.data)
        );
    },
    validarForm() {
      this.errorComponente = 0;
      this.errorMostrarMsjForm = [];
      if (!this.newEvent.event_name)
        this.errorMostrarMsjForm.push("Debe ingresar el nombre del evento");
    
      if (!this.newEvent.start_date)
        this.errorMostrarMsjForm.push("Debe ingresar la fecha de inicio");
      if (!this.newEvent.end_date)
        this.errorMostrarMsjForm.push(
          "Debe ingresar la fecha de finalizacion"
        );
      if(this.newEvent.start_date > this.newEvent.end_date){
          this.errorMostrarMsjForm.push("La fecha de inicio tiene que ser menor a la fecha de finalizacion de contrato");
          //return;
        }  
       
     
      if (this.errorMostrarMsjForm.length) this.errorComponente = 1;
      return this.errorComponente;
    },
    getEvents() {
      let me = this;
      
      axios
        .get("/api/calendar")
        .then(function (response) {
          let respuesta = response.data;
          me.calendarOptions.events = respuesta.data;
         console.log(respuesta.data);
        })
       //.then(resp => (this.events = resp.data.data))
        
        .catch(function (error) {
          console.log(error);
        });
        
    },
    resetForm() {
      Object.keys(this.newEvent).forEach(key => {
        return (this.newEvent[key] = "");
      });
    }
  },
  watch: {
    indexToUpdate() {
      return this.indexToUpdate;
    }
  }
};
</script>

<style lang="css">
@import "~@fullcalendar/core/main.css";
@import "~@fullcalendar/daygrid/main.css";
.fc-title {
  color: #fff;
}
.fc-title:hover {
  cursor: pointer;
}
</style>

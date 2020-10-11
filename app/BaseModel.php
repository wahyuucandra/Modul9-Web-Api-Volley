<?php

namespace App;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use DB;

class BaseModel extends Model
{
    public function save(array $options = []) {
        if( ! is_array($this->getKeyName()))
        {
            return parent::save($options);
        }
        // Fire Event for others to hook
        if($this->fireModelEvent('saving') === false) return false;
        // Prepare query for inserting or updating
        $query = $this->newQueryWithoutScopes();
        // Perform Update
        if ($this->exists)
        {
            if (count($this->getDirty()) > 0)
            {
                // Fire Event for others to hook
                if ($this->fireModelEvent('updating') === false)
                {
                    return false;
                }
                // Touch the timestamps
                if ($this->timestamps)
                {
                    $this->updateTimestamps();
                }
                //
                // START FIX
                //
                // Convert primary key into an array if it's a single value
                $primary = (count($this->getKeyName()) > 1) ? $this->getKeyName() : [$this->getKeyName()];
                // Fetch the primary key(s) values before any changes
                $unique = array_intersect_key($this->original, array_flip($primary));
                // Fetch the primary key(s) values after any changes
                $unique = !empty($unique) ? $unique : array_intersect_key($this->getAttributes(), array_flip($primary));
                // Fetch the element of the array if the array contains only a single element
                //$unique = (count($unique) <> 1) ? $unique : reset($unique);
                // Apply SQL logic
                $query->where($unique);
                //
                // END FIX
                //
                // Update the records
                $query->update($this->getDirty());
                // Fire an event for hooking into
                $this->fireModelEvent('updated', false);
            }
        }
        // Insert
        else
        {
            // Fire an event for hooking into
            if ($this->fireModelEvent('creating') === false) return false;
            // Touch the timestamps
            if($this->timestamps)
            {
                $this->updateTimestamps();
            }
            // Retrieve the attributes
            $attributes = $this->attributes;
            if ($this->incrementing && !is_array($this->getKeyName()))
            {
                $this->insertAndSetId($query, $attributes);
            }
            else
            {
                $query->insert($attributes);
            }
            // Set exists to true in case someone tries to update it during an event
            $this->exists = true;
            // Fire an event for hooking into
            $this->fireModelEvent('created', false);
        }
        // Fires an event
        $this->fireModelEvent('saved', false);
        // Sync
        $this->original = $this->attributes;
        // Touches all relations
        if (Arr::get($options, 'touch', true)) $this->touchOwners();
        return true;
    }    

     /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete()
    {
        if (is_null($this->getKeyName())) {
            throw new Exception('No primary key defined on model.');
        }

        // If the model doesn't exist, there is nothing to delete so we'll just return
        // immediately and not do anything else. Otherwise, we will continue with a
        // deletion process on the model, firing the proper events, and so forth.
        if (! $this->exists) {
            return;
        }

        if ($this->fireModelEvent('deleting') === false) {
            return false;
        }

        // Here, we'll touch the owning models, verifying these timestamps get updated
        // for the models. This will allow any caching to get broken on the parents
        // by the timestamp. Then we will go ahead and delete the model instance.
        $this->touchOwners();

        $this->performDeleteOnModel();

        // Once the model has been deleted, we will fire off the deleted event so that
        // the developers may hook into post-delete operations. We will then return
        // a boolean true as the delete is presumably successful on the database.
        $this->fireModelEvent('deleted', false);

        return true;
    }
}